<?php

namespace App\Http\Controllers;

use App\Models\ProfileSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EvaluasiSemesteranController extends Controller
{
    // Bulan per semester
    private const BULAN_GANJIL = [
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    private const BULAN_GENAP = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'
    ];

    public function index(Request $request)
    {
        $user   = Auth::user();
        $guruId = $user->guru_id;

        // Cek apakah evaluasi diaktifkan oleh admin
        $profil        = ProfileSekolah::first();
        $evaluasiAktif = $profil?->evaluasi_bulanan_aktif ?? false;

        if ($user->role === 'admin') {
            $evaluasiAktif = true;
        }

        // Guard: jika bukan admin dan tidak punya guru_id
        if (!$guruId && $user->role !== 'admin') {
            abort(403, 'Akun Anda tidak terhubung ke data guru.');
        }

        // Tahun ajaran aktif
        $tahun = DB::table('tahun_ajarans')
            ->where('is_active', 1)
            ->first();

        // Jadwal (admin lihat semua, guru lihat miliknya)
        $jadwalsQuery = DB::table('jadwals')
            ->join('mapels', 'mapels.id', '=', 'jadwals.mapel_id')
            ->join('kelas',  'kelas.id',  '=', 'jadwals.kelas_id')
            ->select(
                'jadwals.id',
                'jadwals.kelas_id',
                'jadwals.guru_id',
                'mapels.nama_mapel',
                'kelas.nama_kelas'
            );

        if ($guruId) {
            $jadwalsQuery->where('jadwals.guru_id', $guruId);
        }

        $jadwals = $jadwalsQuery->get()
            ->unique(fn($j) => $j->kelas_id . '-' . $j->nama_mapel);

        $kelasIds  = $jadwals->pluck('kelas_id')->unique()->values();
        $kelasList = DB::table('kelas')
            ->whereIn('id', $kelasIds)
            ->get();

        // Parameter filter
        $jadwalId  = $request->jadwal_id  ?? null;
        $semester  = $request->semester   ?? null;  // 'Ganjil' atau 'Genap'
        $kelasId   = $request->kelas_id   ?? null;

        $filtered = $jadwalId && $semester && $kelasId;
        $data     = [];
        $rincianBulan = [];

        if ($filtered && $evaluasiAktif) {

            $jadwalCheck = DB::table('jadwals')->where('id', $jadwalId);
            if ($guruId) {
                $jadwalCheck->where('guru_id', $guruId);
            }
            $jadwalValid = $jadwalCheck->exists();

            // Resolusi guru_id efektif: admin pakai guru_id dari jadwal
            $effectiveGuruId = $guruId
                ?? DB::table('jadwals')->where('id', $jadwalId)->value('guru_id');

            $kelasValid = $kelasIds->contains($kelasId);

            if ($jadwalValid && $kelasValid) {

                $tahunStr    = $tahun->tahun ?? '';
                $bulanList   = $semester === 'Ganjil'
                    ? self::BULAN_GANJIL
                    : self::BULAN_GENAP;

                $siswas = DB::table('siswas')
                    ->where('kelas_id', $kelasId)
                    ->orderBy('nama')
                    ->get();

                // Kumpulkan nama bulan yang benar-benar ada datanya (untuk header rincian)
                $rincianBulan = $bulanList;

                foreach ($siswas as $siswa) {

                    // ── Akumulasi per bulan ──────────────────────────────
                    $nilaiPerBulan    = [];
                    $absensiPerBulan  = [];
                    $sikapPerBulan    = [];
                    $disiplinPerBulan = [];

                    foreach ($bulanList as $bln) {

                        // Nilai akademik rata-rata bulan ini
                        $nilaiPerBulan[$bln] = (float) (DB::table('nilais')
                            ->where('siswa_id',     $siswa->id)
                            ->where('guru_id',      $effectiveGuruId)
                            ->where('jadwal_id',    $jadwalId)
                            ->where('bulan',        $bln)
                            ->where('tahun_ajaran', $tahunStr)
                            ->avg('nilai') ?? 0);

                        // Absensi: persentase kehadiran bulan ini
                        $total = DB::table('absensi_details as d')
                            ->join('absensis as a', 'a.id', '=', 'd.absensi_id')
                            ->where('d.siswa_id',    $siswa->id)
                            ->where('a.jadwal_id',   $jadwalId)
                            ->where('a.bulan',       $bln)
                            ->where('a.tahun_ajaran', $tahunStr)
                            ->count();

                        $hadir = DB::table('absensi_details as d')
                            ->join('absensis as a', 'a.id', '=', 'd.absensi_id')
                            ->where('d.siswa_id',    $siswa->id)
                            ->where('d.status',      'hadir')
                            ->where('a.jadwal_id',   $jadwalId)
                            ->where('a.bulan',       $bln)
                            ->where('a.tahun_ajaran', $tahunStr)
                            ->count();

                        $absensiPerBulan[$bln] = $total > 0
                            ? round(($hadir / $total) * 100, 2)
                            : 0;

                        // Sikap rata-rata bulan ini
                        $sikapPerBulan[$bln] = (float) (DB::table('sikaps')
                            ->where('siswa_id',     $siswa->id)
                            ->where('guru_id',      $effectiveGuruId)
                            ->where('jadwal_id',    $jadwalId)
                            ->where('bulan',        $bln)
                            ->where('tahun_ajaran', $tahunStr)
                            ->avg('nilai_sikap') ?? 0);

                        // Kedisiplinan rata-rata bulan ini
                        $disiplinPerBulan[$bln] = (float) (DB::table('kedisiplinans')
                            ->where('siswa_id',     $siswa->id)
                            ->where('guru_id',      $effectiveGuruId)
                            ->where('jadwal_id',    $jadwalId)
                            ->where('bulan',        $bln)
                            ->where('tahun_ajaran', $tahunStr)
                            ->avg('nilai_disiplin') ?? 0);
                    }

                    // ── Rata-rata keseluruhan semester ───────────────────
                    $bulanDenganData = count(array_filter(
                        array_map(fn($b) =>
                            $nilaiPerBulan[$b] + $absensiPerBulan[$b] + $sikapPerBulan[$b] + $disiplinPerBulan[$b],
                            $bulanList
                        )
                    ));

                    // Jika tidak ada bulan yang ada datanya, tetap hitung rata-rata dari 6 bulan
                    $pembagi = max(1, count($bulanList));

                    $nilaiRata    = array_sum($nilaiPerBulan)    / $pembagi;
                    $absensiRata  = array_sum($absensiPerBulan)  / $pembagi;
                    $sikapRata    = array_sum($sikapPerBulan)    / $pembagi;
                    $disiplinRata = array_sum($disiplinPerBulan) / $pembagi;

                    // ── Fuzzy Logic (Trapesium + Defuzzifikasi Centroid) ─
                    $normNilai    = $this->clamp($nilaiRata,    0, 100);
                    $normAbsensi  = $this->clamp($absensiRata,  0, 100);
                    $normSikap    = $this->clamp($sikapRata,    0, 100);
                    $normDisiplin = $this->clamp($disiplinRata, 0, 100);

                    $muNilai    = $this->fuzzify($normNilai);
                    $muAbsensi  = $this->fuzzify($normAbsensi);
                    $muSikap    = $this->fuzzify($normSikap);
                    $muDisiplin = $this->fuzzify($normDisiplin);

                    // Agregasi berbobot: Nilai 40%, Absensi 30%, Sikap 15%, Disiplin 15%
                    $skorFuzzy = round(
                        ($muNilai    * 0.40 +
                         $muAbsensi  * 0.30 +
                         $muSikap    * 0.15 +
                         $muDisiplin * 0.15) * 100,
                        2
                    );

                    $adaData = ($normNilai > 0 || $normAbsensi > 0 || $normSikap > 0 || $normDisiplin > 0);

                    $data[] = [
                        'nama'            => $siswa->nama,
                        'nilai'           => round($normNilai,    2),
                        'absensi'         => round($normAbsensi,  2),
                        'sikap'           => round($normSikap,    2),
                        'disiplin'        => round($normDisiplin, 2),
                        'mu_nilai'        => round($muNilai,    4),
                        'mu_absensi'      => round($muAbsensi,  4),
                        'mu_sikap'        => round($muSikap,    4),
                        'mu_disiplin'     => round($muDisiplin, 4),
                        'skor'            => $skorFuzzy,
                        'kategori'        => $this->kategori($skorFuzzy),
                        'ada_data'        => $adaData,
                        // Rincian per bulan untuk modal detail
                        'nilai_per_bulan'    => $nilaiPerBulan,
                        'absensi_per_bulan'  => $absensiPerBulan,
                        'sikap_per_bulan'    => $sikapPerBulan,
                        'disiplin_per_bulan' => $disiplinPerBulan,
                    ];
                }

                usort($data, fn($a, $b) => $b['skor'] <=> $a['skor']);
            }
        }

        return view('evaluasi-semesteran.index', compact(
            'jadwals',
            'kelasList',
            'data',
            'tahun',
            'jadwalId',
            'semester',
            'kelasId',
            'filtered',
            'evaluasiAktif',
            'rincianBulan'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // FUZZY MEMBERSHIP FUNCTIONS (Trapesium)
    // Himpunan: Rendah [0–55], Sedang [40–80], Tinggi [70–100]
    // ─────────────────────────────────────────────────────────────────────────

    private function fuzzify(float $x): float
    {
        $muTinggi = $this->trapezoidUp($x, 70, 80);
        $muSedang = $this->trapezoidFull($x, 40, 55, 70, 80);
        $muRendah = $this->trapezoidDown($x, 40, 55);

        $bobot = $muTinggi + $muSedang + $muRendah;

        if ($bobot <= 0) return 0.0;

        $crisp = ($muTinggi * 100.0 + $muSedang * 67.5 + $muRendah * 27.5) / $bobot;

        return $crisp / 100.0;
    }

    private function trapezoidUp(float $x, float $a, float $b): float
    {
        if ($x <= $a) return 0.0;
        if ($x >= $b) return 1.0;
        return ($x - $a) / ($b - $a);
    }

    private function trapezoidDown(float $x, float $a, float $b): float
    {
        if ($x <= $a) return 1.0;
        if ($x >= $b) return 0.0;
        return ($b - $x) / ($b - $a);
    }

    private function trapezoidFull(float $x, float $a, float $b, float $c, float $d): float
    {
        if ($x <= $a || $x >= $d) return 0.0;
        if ($x >= $b && $x <= $c) return 1.0;
        if ($x < $b) return ($x - $a) / ($b - $a);
        return ($d - $x) / ($d - $c);
    }

    private function clamp(float $val, float $min, float $max): float
    {
        return max($min, min($max, $val));
    }

    private function kategori(float $skor): string
    {
        if ($skor >= 85) return 'Sangat Baik';
        if ($skor >= 70) return 'Baik';
        if ($skor >= 55) return 'Perlu Bimbingan';
        return 'Perlu Pembinaan';
    }
}
