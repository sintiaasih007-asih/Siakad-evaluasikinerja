<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Jadwal;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NilaiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */


    public function index()
    {
        $guruId = auth()->user()->guru_id;

        $jadwals = Jadwal::with([
                'kelas',
                'mapel',
                'guru'
            ])
            ->where('guru_id', $guruId)
            ->orderBy('mapel_id')
            ->get()

            ->unique(function ($item) {

                return $item->mapel_id . '-' . $item->kelas_id;

            })

            ->values();

        return view('nilai.index', compact('jadwals'));
    }

    

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create($id)
    {
        $guruId = Auth::user()->guru_id;

        // validasi agar guru hanya bisa membuka mapel miliknya
        $jadwal = Jadwal::with([
                'kelas',
                'mapel',
                'guru'
            ])
            ->where('guru_id', $guruId)
            ->findOrFail($id);

        $siswas = Siswa::where('kelas_id', $jadwal->kelas_id)
            ->orderBy('nama')
            ->get();

        return view('nilai.create', compact(
            'jadwal',
            'siswas'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            'jadwal_id'      => 'required',
            'jenis_nilai'    => 'required',
            'nama_penilaian' => 'required',
            'siswa_id'       => 'required|array',
            'nilai'          => 'required|array',

        ]);

        $today = Carbon::now();

        // Ambil tahun ajaran aktif dari database agar konsisten dengan evaluasi
        $tahunAjaran = DB::table('tahun_ajarans')->where('is_active', 1)->first();

        // Nama bulan Indonesia yang konsisten
        $namaBulanId = [
            1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
            5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
            9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
        ][$today->month];

        foreach($request->siswa_id as $key => $siswa)
        {
            Nilai::create([

                'siswa_id'       => $siswa,

                'jadwal_id'      => $request->jadwal_id,

                // FIX GURU LOGIN
                'guru_id'        => Auth::user()->guru_id,

                'jenis_nilai'    => $request->jenis_nilai,

                'nama_penilaian' => $request->nama_penilaian,

                'nilai'          => $request->nilai[$key],

                'tanggal'        => $today,

                'bulan'          => $namaBulanId,

                'semester'       => $today->month <= 6
                                        ? 'Genap'
                                        : 'Ganjil',

                'tahun_ajaran'   => $tahunAjaran?->tahun
                                        ?? ($today->year . '/' . ($today->year + 1)),
            ]);
        }

        return redirect()
            ->route('nilai.index')
            ->with('success', 'Nilai berhasil disimpan');
    }
}