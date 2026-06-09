<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\AbsensiDetail;
use App\Models\Jadwal;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        Carbon::setLocale('id');

        // untuk tampilan header
        $hariIni = Carbon::now()->translatedFormat('l, d F Y');

        // khusus query jadwal
        $hari = Carbon::now()->translatedFormat('l');

        // ambil guru login
        $guruId = auth()->user()->guru_id;

        // validasi
        if (!$guruId) {
            abort(403, 'Guru tidak ditemukan.');
        }

        // ambil jadwal guru login
        $jadwals = Jadwal::with(['kelas','mapel','guru'])
            ->where('hari', $hari)
            ->where('guru_id', $guruId)
            ->orderBy('jam_masuk')
            ->get();


        // daftar kelas yang diampu guru
        $kelasDiampu = Jadwal::with('kelas')
        ->where('guru_id', $guruId)
        ->get()
        ->pluck('kelas')
        ->unique('id')
        ->values();


        $riwayatAbsensi = [];

        foreach ($kelasDiampu as $kelas) {

            $riwayatAbsensi[$kelas->id] = Absensi::with([
                    'jadwal.mapel',
                    'details.siswa'
                ])
                ->whereHas('jadwal', function($q) use ($kelas) {
                    $q->where('kelas_id', $kelas->id);
                })
                ->orderBy('tanggal', 'desc')
                ->get();
        }

        return view('absensi.index', compact(
            'jadwals',
            'hariIni',
            'kelasDiampu',
            'riwayatAbsensi'
        ));

        
    }

    /*
    |--------------------------------------------------------------------------
    | FORM INPUT ABSENSI
    |--------------------------------------------------------------------------
    */
    public function create($id)
    {
        $jadwal = Jadwal::with(['kelas','mapel','guru'])->findOrFail($id);

        $siswas = Siswa::where('kelas_id', $jadwal->kelas_id)
                    ->orderBy('nama')
                    ->get();

        $pertemuan = Absensi::where('jadwal_id', $jadwal->id)->count() + 1;

        return view('absensi.create', compact(
            'jadwal',
            'siswas',
            'pertemuan'
        ));
    }


    public function updateDetail(Request $request)
    {
        $request->validate([
            'detail_id' => 'required',
            'status'    => 'required'
        ]);

        $detail = AbsensiDetail::findOrFail($request->detail_id);

        $detail->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE AUTO SAVE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'jadwal_id' => 'required',
                'tanggal'   => 'required|date',
            ]);

            $absensi = Absensi::updateOrCreate(
                [
                    'jadwal_id' => $request->jadwal_id,
                    'tanggal'   => $request->tanggal,
                ],
                [
                    'pertemuan' => $request->pertemuan ?? null,
                ]
            );

            if ($request->status) {

                foreach ($request->status as $siswaId => $status) {

                    AbsensiDetail::updateOrCreate(
                        [
                            'absensi_id' => $absensi->id,
                            'siswa_id'   => $siswaId,
                        ],
                        [
                            'status' => $status
                        ]
                    );
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Absensi tersimpan'
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REKAP BULANAN (SIAP FUZZY)
    |--------------------------------------------------------------------------
    */
    public function rekapBulanan()
    {
        $bulan = now()->month;
        $tahun = now()->year;

        $siswas = Siswa::with('kelas')->get();

        $data = [];

        foreach ($siswas as $s) {

            $detail = AbsensiDetail::where('siswa_id', $s->id)
                ->whereHas('absensi', function($q) use ($bulan,$tahun){
                    $q->whereMonth('tanggal',$bulan)
                      ->whereYear('tanggal',$tahun);
                });

            $hadir = (clone $detail)->where('status','hadir')->count();
            $izin  = (clone $detail)->where('status','izin')->count();
            $sakit = (clone $detail)->where('status','sakit')->count();
            $alpha = (clone $detail)->where('status','alpha')->count();

            $total = $hadir + $izin + $sakit + $alpha;

            $persen = $total > 0
                ? round(($hadir / $total) * 100,2)
                : 0;

            /*
            ===========================================
            NILAI DISIPLIN UNTUK FUZZY
            ===========================================
            */

            $nilaiDisiplin = $persen;

            $data[] = [
                'siswa'          => $s->nama,
                'kelas'          => $s->kelas->nama_kelas ?? '-',
                'hadir'          => $hadir,
                'izin'           => $izin,
                'sakit'          => $sakit,
                'alpha'          => $alpha,
                'persentase'     => $persen,
                'nilai_disiplin' => $nilaiDisiplin,
            ];
        }

        return view('absensi.rekap', compact('data'));
    }

    /*
    |--------------------------------------------------------------------------
    | REKAP SEMESTER (SIAP FUZZY)
    |--------------------------------------------------------------------------
    */
    public function rekapSemester()
    {
        $tahun = now()->year;
        $bulan = now()->month;

        $semester = $bulan <= 6 ? [1,2,3,4,5,6] : [7,8,9,10,11,12];

        $siswas = Siswa::with('kelas')->get();

        $data = [];

        foreach ($siswas as $s) {

            $detail = AbsensiDetail::where('siswa_id', $s->id)
                ->whereHas('absensi', function($q) use ($semester,$tahun){
                    $q->whereIn(DB::raw('MONTH(tanggal)'), $semester)
                      ->whereYear('tanggal',$tahun);
                });

            $hadir = (clone $detail)->where('status','hadir')->count();
            $izin  = (clone $detail)->where('status','izin')->count();
            $sakit = (clone $detail)->where('status','sakit')->count();
            $alpha = (clone $detail)->where('status','alpha')->count();

            $total = $hadir + $izin + $sakit + $alpha;

            $persen = $total > 0
                ? round(($hadir / $total) * 100,2)
                : 0;

            $data[] = [
                'siswa' => $s->nama,
                'kelas' => $s->kelas->nama_kelas ?? '-',
                'persentase' => $persen,
                'nilai_disiplin' => $persen
            ];
        }

        return view('absensi.rekap-semester', compact('data'));
    }
}