<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\FuzzyMamdaniService;

class RekapEvaluasiKelasController extends Controller
{
    protected $fuzzy;

    public function __construct(FuzzyMamdaniService $fuzzy)
    {

        $this->fuzzy = $fuzzy;
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        // =========================
        // TAHUN AJARAN AKTIF (WAJIB FILTER)
        // =========================
        $tahun = DB::table('tahun_ajarans')
            ->where('is_active', 1)
            ->first();

        if (!$tahun) {
            return view('rekap-evaluasi-kelas.index', [
                'kelas' => null,
                'tahun' => null,
                'bulan' => null,
                'data' => collect()
            ]);
        }

        // =========================
        // BULAN FILTER (DEFAULT BULAN INI)
        // =========================
        $bulan = $request->bulan ?? date('m');

        $bulanList = [
            '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April',
            '05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus',
            '09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
        ];

        $bulanText = $bulanList[$bulan] ?? date('F');

        // =========================
        // KELAS WALI KELAS
        // =========================
        $kelas = DB::table('kelas')
            ->where('guru_id', $user->guru_id)
            ->first();

        if (!$kelas) {
            return view('rekap-evaluasi-kelas.index', [
                'kelas' => null,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'data' => collect()
            ]);
        }

        // =========================
        // AMBIL SISWA SEKALI (OPTIMASI)
        // =========================
        $siswas = DB::table('siswas')
            ->where('kelas_id', $kelas->id)
            ->orderBy('nama')
            ->get();

        $data = collect();

        foreach ($siswas as $siswa) {

            // =========================
            // NILAI (AVG)
            // =========================
            $nilai = DB::table('nilais')
                ->where('siswa_id', $siswa->id)
                ->where('tahun_ajaran', $tahun->tahun)
                ->where('semester', $tahun->semester)
                ->where('bulan', $bulanText)
                ->avg('nilai') ?? 0;

            // =========================
            // ABSENSI %
            // =========================
            $totalAbsensi = DB::table('absensi_details as d')
                ->join('absensis as a', 'a.id', '=', 'd.absensi_id')
                ->where('d.siswa_id', $siswa->id)
                ->where('a.tahun_ajaran', $tahun->tahun)
                ->where('a.semester', $tahun->semester)
                ->where('a.bulan', $bulanText)
                ->count();

            $hadir = DB::table('absensi_details as d')
                ->join('absensis as a', 'a.id', '=', 'd.absensi_id')
                ->where('d.siswa_id', $siswa->id)
                ->where('d.status', 'hadir')
                ->where('a.tahun_ajaran', $tahun->tahun)
                ->where('a.semester', $tahun->semester)
                ->where('a.bulan', $bulanText)
                ->count();

            $absensi = $totalAbsensi > 0
                ? round(($hadir / $totalAbsensi) * 100)
                : 0;

            // =========================
            // SIKAP
            // =========================
            $sikap = DB::table('sikaps')
                ->where('siswa_id', $siswa->id)
                ->where('tahun_ajaran', $tahun->tahun)
                ->where('semester', $tahun->semester)
                ->where('bulan', $bulanText)
                ->avg('nilai_sikap') ?? 0;

            // =========================
            // DISIPLIN
            // =========================
            $disiplin = DB::table('kedisiplinans')
                ->where('siswa_id', $siswa->id)
                ->where('tahun_ajaran', $tahun->tahun)
                ->where('semester', $tahun->semester)
                ->where('bulan', $bulanText)
                ->avg('nilai_disiplin') ?? 0;

            // =========================
            // FUZZY MAMDANI
            // =========================
            $hasil = $this->fuzzy->hitung(
                $nilai,
                $absensi,
                $sikap,
                $disiplin
            );

            // =========================
            // PUSH DATA
            // =========================
            $data->push([
                'id'       => $siswa->id,
                'nis'      => $siswa->nis,
                'nama'     => $siswa->nama,
                'nilai'    => round($nilai),
                'absensi'  => $absensi,
                'sikap'    => round($sikap),
                'disiplin' => round($disiplin),

                'hasil'    => $hasil['skor'] ?? 0,
                'kategori' => $hasil['kategori'] ?? 'Perlu Pembinaan',
                'detail'   => $hasil['detail'] ?? [],
            ]);
        }

        // =========================
        // RANKING AMAN (JIKA SEMUA 0 TETAP URUT STABIL)
        // =========================
        $data = $data->sortByDesc(function ($item) {
            return $item['hasil'] ?? 0;
        })->values();

        return view('rekap-evaluasi-kelas.index', [
            'kelas' => $kelas,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'data'  => $data
        ]);
    }
}