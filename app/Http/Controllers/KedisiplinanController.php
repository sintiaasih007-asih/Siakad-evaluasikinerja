<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Kedisiplinan;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KedisiplinanController extends Controller
{
    public function index()
    {
        $guruId = auth()->user()->guru_id;

        $jadwals = Jadwal::with('kelas', 'mapel', 'guru')
            ->where('guru_id', $guruId)
            ->orderBy('mapel_id')
            ->get()
            ->unique(fn($j) => $j->mapel_id . '-' . $j->kelas_id)
            ->values();

        return view('kedisiplinan.index', compact('jadwals'));
    }

    public function create($id)
    {
        $guruId = auth()->user()->guru_id;

        $jadwal = Jadwal::with('kelas', 'mapel', 'guru')
            ->where('guru_id', $guruId)
            ->findOrFail($id);

        $siswas = Siswa::where('kelas_id', $jadwal->kelas_id)
            ->orderBy('nama')
            ->get();

        return view('kedisiplinan.create', compact('jadwal', 'siswas'));
    }

    public function store(Request $request)
    {
        // Ambil tahun ajaran aktif dari database agar konsisten dengan evaluasi
        $tahunAjaran = DB::table('tahun_ajarans')->where('is_active', 1)->first();

        foreach ($request->siswa_id as $key => $siswaId) {
            $today = Carbon::now();

            // Nama bulan Indonesia yang konsisten
            $namaBulanId = [
                1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
                5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
                9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
            ][$today->month];

            Kedisiplinan::create([
                'siswa_id'       => $siswaId,
                'jadwal_id'      => $request->jadwal_id,
                'guru_id'        => auth()->user()->guru_id,
                'tanggal'        => $today->toDateString(),
                'bulan'          => $namaBulanId,
                'semester'       => $today->month <= 6 ? 'Genap' : 'Ganjil',
                'tahun_ajaran'   => $tahunAjaran?->tahun
                                        ?? ($today->year . '/' . ($today->year + 1)),
                'nilai_disiplin' => $request->nilai_disiplin[$key],
                'keterangan'     => $request->keterangan[$key] ?? null,
            ]);
        }

        return redirect()->route('kedisiplinan.index')
            ->with('success', 'Data kedisiplinan berhasil disimpan.');
    }

    /** JSON riwayat untuk modal */
    public function riwayat($jadwalId)
    {
        $guruId = auth()->user()->guru_id;

        $data = Kedisiplinan::with('siswa')
            ->where('jadwal_id', $jadwalId)
            ->where('guru_id', $guruId)
            ->orderByDesc('tanggal')
            ->get()
            ->groupBy('tanggal');

        return response()->json($data);
    }
}
