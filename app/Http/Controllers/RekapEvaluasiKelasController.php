<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\FuzzyMamdaniService;

class RekapEvaluasiKelasController extends Controller
{
    protected FuzzyMamdaniService $fuzzy;

    public function __construct(FuzzyMamdaniService $fuzzy)
    {
        $this->fuzzy = $fuzzy;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // NAMA BULAN (nomor → teks Indonesia)
    // ─────────────────────────────────────────────────────────────────────────
    private const BULAN_MAP = [
        '01' => 'Januari',  '02' => 'Februari', '03' => 'Maret',
        '04' => 'April',    '05' => 'Mei',       '06' => 'Juni',
        '07' => 'Juli',     '08' => 'Agustus',   '09' => 'September',
        '10' => 'Oktober',  '11' => 'November',  '12' => 'Desember',
    ];

    public function index(Request $request)
    {
        $user   = Auth::user();
        $guruId = $user->guru_id;

        // ── Tahun ajaran aktif ────────────────────────────────────────────
        $tahun = DB::table('tahun_ajarans')
            ->where('is_active', 1)
            ->first();

        // ── Kelas yang diwali-kelasi guru yang login ──────────────────────
        $kelas = null;
        if ($guruId) {
            $kelas = DB::table('kelas')
                ->where('guru_id', $guruId)
                ->first();
        }

        // ── Filter bulan (default: bulan berjalan) ────────────────────────
        $bulan     = $request->bulan ?? date('m');
        $namaBulan = self::BULAN_MAP[$bulan] ?? date('F');
        $tahunStr  = $tahun->tahun ?? '';

        // ── Data kosong jika tidak ada kelas atau tahun ajaran ────────────
        $data      = collect();
        $filtered  = $request->has('bulan');

        if (!$kelas || !$tahun) {
            return view('rekap-evaluasi-kelas.index', [
                'kelas'     => $kelas,
                'tahun'     => $tahun,
                'bulan'     => $bulan,
                'namaBulan' => $namaBulan,
                'data'      => $data,
                'filtered'  => $filtered,
            ]);
        }

        // ── Ambil semua siswa di kelas ini ────────────────────────────────
        $siswas = DB::table('siswas')
            ->where('kelas_id', $kelas->id)
            ->orderBy('nama')
            ->get();

        // ── Ambil semua jadwal di kelas ini (dari semua guru) ─────────────
        // Nilai, sikap, dan disiplin diakumulasi dari SEMUA jadwal di kelas,
        // karena wali kelas perlu gambaran menyeluruh performa tiap siswa.
        $jadwalIds = DB::table('jadwals')
            ->where('kelas_id', $kelas->id)
            ->pluck('id');

        foreach ($siswas as $siswa) {

            // ── 1. NILAI AKADEMIK (rata-rata semua mapel bulan ini) ───────
            $nilaiAkademik = DB::table('nilais')
                ->where('siswa_id',     $siswa->id)
                ->whereIn('jadwal_id',  $jadwalIds)
                ->where('bulan',        $namaBulan)
                ->where('tahun_ajaran', $tahunStr)
                ->avg('nilai') ?? 0;

            // ── 2. ABSENSI (% kehadiran dari semua pertemuan bulan ini) ───
            $totalPertemuan = DB::table('absensi_details as d')
                ->join('absensis as a', 'a.id', '=', 'd.absensi_id')
                ->where('d.siswa_id',    $siswa->id)
                ->whereIn('a.jadwal_id', $jadwalIds)
                ->where('a.bulan',       $namaBulan)
                ->where('a.tahun_ajaran', $tahunStr)
                ->count();

            $hadirCount = DB::table('absensi_details as d')
                ->join('absensis as a', 'a.id', '=', 'd.absensi_id')
                ->where('d.siswa_id',    $siswa->id)
                ->where('d.status',      'hadir')
                ->whereIn('a.jadwal_id', $jadwalIds)
                ->where('a.bulan',       $namaBulan)
                ->where('a.tahun_ajaran', $tahunStr)
                ->count();

            $persenHadir = $totalPertemuan > 0
                ? round(($hadirCount / $totalPertemuan) * 100, 2)
                : 0;

            // ── 3. SIKAP (rata-rata semua guru bulan ini) ─────────────────
            $nilaiSikap = DB::table('sikaps')
                ->where('siswa_id',     $siswa->id)
                ->whereIn('jadwal_id',  $jadwalIds)
                ->where('bulan',        $namaBulan)
                ->where('tahun_ajaran', $tahunStr)
                ->avg('nilai_sikap') ?? 0;

            // ── 4. KEDISIPLINAN (rata-rata semua guru bulan ini) ──────────
            $nilaiDisiplin = DB::table('kedisiplinans')
                ->where('siswa_id',     $siswa->id)
                ->whereIn('jadwal_id',  $jadwalIds)
                ->where('bulan',        $namaBulan)
                ->where('tahun_ajaran', $tahunStr)
                ->avg('nilai_disiplin') ?? 0;

            // ── 5. FUZZY MAMDANI (service yang sudah ada) ─────────────────
            $hasil = $this->fuzzy->hitung(
                (float) $nilaiAkademik,
                (float) $persenHadir,
                (float) $nilaiSikap,
                (float) $nilaiDisiplin
            );

            $adaData = ($nilaiAkademik > 0 || $totalPertemuan > 0
                     || $nilaiSikap > 0    || $nilaiDisiplin > 0);

            $data->push([
                'id'       => $siswa->id,
                'nis'      => $siswa->nis ?? '-',
                'nama'     => $siswa->nama,
                'nilai'    => round($nilaiAkademik, 2),
                'absensi'  => round($persenHadir,   2),
                'sikap'    => round($nilaiSikap,     2),
                'disiplin' => round($nilaiDisiplin,  2),
                'skor'     => $hasil['skor']     ?? 0,
                'kategori' => $hasil['kategori'] ?? 'Perlu Pembinaan',
                'ada_data' => $adaData,
            ]);
        }

        // ── Urutkan berdasarkan skor tertinggi ────────────────────────────
        $data = $data->sortByDesc('skor')->values();

        return view('rekap-evaluasi-kelas.index', compact(
            'kelas', 'tahun', 'bulan', 'namaBulan', 'data', 'filtered'
        ));
    }
}
