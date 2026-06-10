<?php

namespace App\Http\Controllers;

use App\Models\ProfileSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EvaluasiBulananController extends Controller
{
    public function index(Request $request)
    {
        $user   = Auth::user();
        $guruId = $user->guru_id;

        // Cek apakah evaluasi bulanan diaktifkan oleh admin
        $profil        = ProfileSekolah::first();
        $evaluasiAktif = $profil?->evaluasi_bulanan_aktif ?? false;

        // Admin selalu bisa akses; jika admin tidak punya guru_id, tampilkan semua jadwal
        if ($user->role === 'admin') {
            $evaluasiAktif = true;
        }

        // Guard: jika bukan admin dan tidak punya guru_id, tolak akses
        if (!$guruId && $user->role !== 'admin') {
            abort(403, 'Akun Anda tidak terhubung ke data guru.');
        }

        // Tahun ajaran aktif
        $tahun = DB::table('tahun_ajarans')
            ->where('is_active', 1)
            ->first();

        // Jadwal milik guru yang login (mapel + kelas unik)
        // Jika admin tanpa guru_id → tampilkan semua jadwal
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

        // Kelas yang diampu guru ini saja
        $kelasIds  = $jadwals->pluck('kelas_id')->unique()->values();
        $kelasList = DB::table('kelas')
            ->whereIn('id', $kelasIds)
            ->get();

        // Parameter filter
        $jadwalId = $request->jadwal_id ?? null;
        $bulan    = $request->bulan    ?? null;
        $kelasId  = $request->kelas_id ?? null;

        // Tabel hasil hanya muncul jika semua filter sudah dipilih
        $filtered = $jadwalId && $bulan && $kelasId;
        $data     = [];

        if ($filtered && $evaluasiAktif) {

            // Pastikan jadwal yang dipilih milik guru ini
            // (admin tanpa guru_id bisa akses semua jadwal)
            $jadwalCheck = DB::table('jadwals')->where('id', $jadwalId);
            if ($guruId) {
                $jadwalCheck->where('guru_id', $guruId);
            }
            $jadwalValid = $jadwalCheck->exists();

            // Resolusi guru_id efektif: admin pakai guru_id dari jadwal
            $effectiveGuruId = $guruId
                ?? DB::table('jadwals')->where('id', $jadwalId)->value('guru_id');

            // Pastikan kelas yang dipilih termasuk kelas yang diampu
            $kelasValid = $kelasIds->contains($kelasId);

            if ($jadwalValid && $kelasValid) {

                $namaBulan = $this->namaBulan($bulan);
                $tahunStr  = $tahun->tahun ?? '';

                $siswas = DB::table('siswas')
                    ->where('kelas_id', $kelasId)
                    ->orderBy('nama')
                    ->get();

                foreach ($siswas as $siswa) {

                    // ── 1. NILAI AKADEMIK (rata-rata semua nilai di bulan tsb) ──
                    $nilaiAkademik = DB::table('nilais')
                        ->where('siswa_id',    $siswa->id)
                        ->where('guru_id',     $effectiveGuruId)
                        ->where('jadwal_id',   $jadwalId)
                        ->where('bulan',       $namaBulan)
                        ->where('tahun_ajaran', $tahunStr)
                        ->avg('nilai') ?? 0;

                    // ── 2. ABSENSI (persentase kehadiran di bulan tsb) ──
                    $totalPertemuan = DB::table('absensi_details as d')
                        ->join('absensis as a', 'a.id', '=', 'd.absensi_id')
                        ->where('d.siswa_id',    $siswa->id)
                        ->where('a.jadwal_id',   $jadwalId)
                        ->where('a.bulan',       $namaBulan)
                        ->where('a.tahun_ajaran', $tahunStr)
                        ->count();

                    $hadirCount = DB::table('absensi_details as d')
                        ->join('absensis as a', 'a.id', '=', 'd.absensi_id')
                        ->where('d.siswa_id',    $siswa->id)
                        ->where('d.status',      'hadir')
                        ->where('a.jadwal_id',   $jadwalId)
                        ->where('a.bulan',       $namaBulan)
                        ->where('a.tahun_ajaran', $tahunStr)
                        ->count();

                    // Persentase kehadiran (0–100)
                    $persenHadir = $totalPertemuan > 0
                        ? round(($hadirCount / $totalPertemuan) * 100, 2)
                        : 0;

                    // ── 3. SIKAP (rata-rata nilai sikap di bulan tsb) ──
                    $nilaiSikap = DB::table('sikaps')
                        ->where('siswa_id',    $siswa->id)
                        ->where('guru_id',     $effectiveGuruId)
                        ->where('jadwal_id',   $jadwalId)
                        ->where('bulan',       $namaBulan)
                        ->where('tahun_ajaran', $tahunStr)
                        ->avg('nilai_sikap') ?? 0;

                    // ── 4. KEDISIPLINAN (rata-rata nilai disiplin di bulan tsb) ──
                    $nilaiDisiplin = DB::table('kedisiplinans')
                        ->where('siswa_id',    $siswa->id)
                        ->where('guru_id',     $effectiveGuruId)
                        ->where('jadwal_id',   $jadwalId)
                        ->where('bulan',       $namaBulan)
                        ->where('tahun_ajaran', $tahunStr)
                        ->avg('nilai_disiplin') ?? 0;

                    // ── FUZZY LOGIC (Weighted Mamdani-style) ──────────────────
                    // Normalisasi semua komponen ke skala 0–100
                    $normNilai    = $this->clamp($nilaiAkademik, 0, 100);
                    $normAbsensi  = $this->clamp($persenHadir,   0, 100);
                    $normSikap    = $this->clamp($nilaiSikap,    0, 100);
                    $normDisiplin = $this->clamp($nilaiDisiplin, 0, 100);

                    // Fuzzifikasi — derajat keanggotaan tiap komponen
                    $muNilai    = $this->fuzzify($normNilai);
                    $muAbsensi  = $this->fuzzify($normAbsensi);
                    $muSikap    = $this->fuzzify($normSikap);
                    $muDisiplin = $this->fuzzify($normDisiplin);

                    // Agregasi berbobot (inference + defuzzifikasi via weighted average)
                    // Bobot: Nilai 40%, Absensi 30%, Sikap 15%, Disiplin 15%
                    $skorFuzzy = (
                        ($muNilai    * 0.40) +
                        ($muAbsensi  * 0.30) +
                        ($muSikap    * 0.15) +
                        ($muDisiplin * 0.15)
                    ) * 100;  // kembalikan ke skala 0–100

                    $skorFuzzy = round($skorFuzzy, 2);

                    $data[] = [
                        'nama'      => $siswa->nama,
                        'nilai'     => round($normNilai, 2),
                        'absensi'   => round($normAbsensi, 2),
                        'sikap'     => round($normSikap, 2),
                        'disiplin'  => round($normDisiplin, 2),
                        // Derajat keanggotaan fuzzy (0.0 – 1.0)
                        'mu_nilai'    => round($muNilai, 4),
                        'mu_absensi'  => round($muAbsensi, 4),
                        'mu_sikap'    => round($muSikap, 4),
                        'mu_disiplin' => round($muDisiplin, 4),
                        'skor'      => $skorFuzzy,
                        'kategori'  => $this->kategori($skorFuzzy),
                        // Info apakah ada data
                        'ada_data'  => ($normNilai > 0 || $totalPertemuan > 0 || $normSikap > 0 || $normDisiplin > 0),
                    ];
                }

                // Urutkan berdasarkan skor tertinggi
                usort($data, fn($a, $b) => $b['skor'] <=> $a['skor']);
            }
        }

        return view('evaluasi-bulanan.index', compact(
            'jadwals',
            'kelasList',
            'data',
            'tahun',
            'jadwalId',
            'bulan',
            'kelasId',
            'filtered',
            'evaluasiAktif'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // FUZZY MEMBERSHIP FUNCTION
    // Menggunakan fungsi trapesium (trapezoidal) untuk membuat nilai fuzzy
    // yang lebih halus daripada threshold linear sederhana.
    //
    //  Rendah  : [0, 0, 40, 60]
    //  Sedang  : [40, 60, 75]    (segitiga)
    //  Tinggi  : [60, 75, 100, 100]
    //
    // Keluaran: derajat keanggotaan "Tinggi" (0.0 – 1.0)
    // Semakin besar nilai input → semakin mendekati 1.0
    // ─────────────────────────────────────────────────────────────────────────
    private function fuzzify(float $x): float
    {
        // Himpunan "Sangat Rendah": [0, 0, 40, 55]
        // Himpunan "Sedang"       : [40, 55, 70, 80]
        // Himpunan "Tinggi"       : [70, 80, 100, 100]
        //
        // Kita cukup hitung derajat keanggotaan himpunan "Tinggi" sebagai
        // representasi performa — makin tinggi nilai, makin tinggi derajat.
        // Fungsi trapesium naik dari 70 → 1.0 pada 80, plateau sampai 100.
        //
        // Untuk "Sedang": naik dari 40 → puncak di 55–70, turun ke 80.
        // Untuk evaluasi final kita gunakan weighted membership dari ketiga himpunan.

        // Derajat keanggotaan "Tinggi" (trapesium naik)
        $muTinggi = $this->trapezoidUp($x, 70, 80);

        // Derajat keanggotaan "Sedang" (trapesium penuh)
        $muSedang = $this->trapezoidFull($x, 40, 55, 70, 80);

        // Derajat keanggotaan "Rendah" (trapesium turun)
        $muRendah = $this->trapezoidDown($x, 40, 55);

        // Defuzzifikasi centroid sederhana:
        // Output crisp = (muTinggi×100 + muSedang×67.5 + muRendah×27.5)
        //               / (muTinggi + muSedang + muRendah)
        $bobot = $muTinggi + $muSedang + $muRendah;

        if ($bobot <= 0) {
            return 0.0;
        }

        $crisp = ($muTinggi * 100.0 + $muSedang * 67.5 + $muRendah * 27.5) / $bobot;

        // Normalkan kembali ke 0–1
        return $crisp / 100.0;
    }

    /** Trapesium naik: 0 sebelum a, naik dari a ke b, 1 setelah b */
    private function trapezoidUp(float $x, float $a, float $b): float
    {
        if ($x <= $a) return 0.0;
        if ($x >= $b) return 1.0;
        return ($x - $a) / ($b - $a);
    }

    /** Trapesium turun: 1 sebelum a, turun dari a ke b, 0 setelah b */
    private function trapezoidDown(float $x, float $a, float $b): float
    {
        if ($x <= $a) return 1.0;
        if ($x >= $b) return 0.0;
        return ($b - $x) / ($b - $a);
    }

    /** Trapesium penuh: naik dari a ke b, plateau dari b ke c, turun dari c ke d */
    private function trapezoidFull(float $x, float $a, float $b, float $c, float $d): float
    {
        if ($x <= $a || $x >= $d) return 0.0;
        if ($x >= $b && $x <= $c) return 1.0;
        if ($x < $b) return ($x - $a) / ($b - $a);
        return ($d - $x) / ($d - $c);
    }

    /** Clamp nilai antara min dan max */
    private function clamp(float $val, float $min, float $max): float
    {
        return max($min, min($max, $val));
    }

    /** Kategori berdasarkan skor fuzzy (skala 0–100) */
    private function kategori(float $skor): string
    {
        if ($skor >= 85) return 'Sangat Baik';
        if ($skor >= 70) return 'Baik';
        if ($skor >= 55) return 'Perlu Bimbingan';
        return 'Perlu Pembinaan';
    }

    /** Konversi nomor bulan (01–12) ke nama bulan Bahasa Indonesia */
    private function namaBulan(string $bulan): string
    {
        return [
            '01' => 'Januari',  '02' => 'Februari', '03' => 'Maret',
            '04' => 'April',    '05' => 'Mei',       '06' => 'Juni',
            '07' => 'Juli',     '08' => 'Agustus',   '09' => 'September',
            '10' => 'Oktober',  '11' => 'November',  '12' => 'Desember',
        ][$bulan] ?? $bulan;
    }
}
