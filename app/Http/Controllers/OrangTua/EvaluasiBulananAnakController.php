<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Siswa;

class EvaluasiBulananAnakController extends Controller
{
    private const BULAN_MAP = [
        '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April',
        '05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus',
        '09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember',
    ];

    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user->siswa_id) {
            return view('orangtua.evaluasi-bulanan-anak', ['siswa' => null]);
        }

        $siswa      = Siswa::with('kelas')->findOrFail($user->siswa_id);
        $tahunAktif = DB::table('tahun_ajarans')->where('is_active', 1)->first();
        $tahunStr   = $tahunAktif->tahun ?? '';

        // ── Filter bulan (default bulan berjalan) ──────────────────────────
        $bulanFilter = $request->bulan ?? date('m');
        $namaBulan   = self::BULAN_MAP[$bulanFilter] ?? '';

        // ── Semua jadwal yang pernah ada data untuk siswa ini ──────────────
        $jadwalIds = DB::table('jadwals')
            ->where('kelas_id', $siswa->kelas_id)
            ->pluck('id');

        // ── 1. NILAI AKADEMIK ──────────────────────────────────────────────
        $nilaiAkademik = DB::table('nilais')
            ->where('siswa_id',     $siswa->id)
            ->whereIn('jadwal_id',  $jadwalIds)
            ->where('bulan',        $namaBulan)
            ->where('tahun_ajaran', $tahunStr)
            ->avg('nilai') ?? 0;

        // Detail nilai per mapel bulan ini
        $nilaiPerMapel = DB::table('nilais as n')
            ->join('jadwals as j', 'j.id', '=', 'n.jadwal_id')
            ->join('mapels as m',  'm.id', '=', 'j.mapel_id')
            ->where('n.siswa_id',     $siswa->id)
            ->whereIn('n.jadwal_id',  $jadwalIds)
            ->where('n.bulan',        $namaBulan)
            ->where('n.tahun_ajaran', $tahunStr)
            ->select('m.nama_mapel', DB::raw('AVG(n.nilai) as avg_nilai'), DB::raw('COUNT(*) as cnt'))
            ->groupBy('m.nama_mapel')
            ->orderBy('m.nama_mapel')
            ->get();

        // ── 2. ABSENSI ─────────────────────────────────────────────────────
        $totalPertemuan = DB::table('absensi_details as d')
            ->join('absensis as a', 'a.id', '=', 'd.absensi_id')
            ->where('d.siswa_id',    $siswa->id)
            ->whereIn('a.jadwal_id', $jadwalIds)
            ->where('a.bulan',       $namaBulan)
            ->where('a.tahun_ajaran', $tahunStr)
            ->count();

        $absensiDetail = DB::table('absensi_details as d')
            ->join('absensis as a', 'a.id', '=', 'd.absensi_id')
            ->where('d.siswa_id',    $siswa->id)
            ->whereIn('a.jadwal_id', $jadwalIds)
            ->where('a.bulan',       $namaBulan)
            ->where('a.tahun_ajaran', $tahunStr)
            ->selectRaw('
                SUM(CASE WHEN d.status="hadir" THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN d.status="izin"  THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN d.status="sakit" THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN d.status="alpha" THEN 1 ELSE 0 END) as alpha
            ')
            ->first();

        $persenHadir = $totalPertemuan > 0
            ? round(($absensiDetail->hadir / $totalPertemuan) * 100, 2)
            : 0;

        // ── 3. SIKAP ───────────────────────────────────────────────────────
        $nilaiSikap = DB::table('sikaps')
            ->where('siswa_id',     $siswa->id)
            ->whereIn('jadwal_id',  $jadwalIds)
            ->where('bulan',        $namaBulan)
            ->where('tahun_ajaran', $tahunStr)
            ->avg('nilai_sikap') ?? 0;

        // ── 4. DISIPLIN ────────────────────────────────────────────────────
        $nilaiDisiplin = DB::table('kedisiplinans')
            ->where('siswa_id',     $siswa->id)
            ->whereIn('jadwal_id',  $jadwalIds)
            ->where('bulan',        $namaBulan)
            ->where('tahun_ajaran', $tahunStr)
            ->avg('nilai_disiplin') ?? 0;

        // ── FUZZY LOGIC (Trapesium + Defuzzifikasi Centroid) ───────────────
        $norm = [
            'nilai'    => $this->clamp($nilaiAkademik, 0, 100),
            'absensi'  => $this->clamp($persenHadir,   0, 100),
            'sikap'    => $this->clamp($nilaiSikap,     0, 100),
            'disiplin' => $this->clamp($nilaiDisiplin,  0, 100),
        ];

        $mu = [
            'nilai'    => $this->fuzzify($norm['nilai']),
            'absensi'  => $this->fuzzify($norm['absensi']),
            'sikap'    => $this->fuzzify($norm['sikap']),
            'disiplin' => $this->fuzzify($norm['disiplin']),
        ];

        // Bobot: nilai 40%, absensi 30%, sikap 15%, disiplin 15%
        $skorFuzzy = round(
            ($mu['nilai'] * 0.40 + $mu['absensi'] * 0.30
           + $mu['sikap'] * 0.15 + $mu['disiplin'] * 0.15) * 100,
            2
        );

        $adaData = ($norm['nilai'] > 0 || $totalPertemuan > 0
                 || $norm['sikap'] > 0  || $norm['disiplin'] > 0);

        $hasil = [
            'nilai'    => round($norm['nilai'],    2),
            'absensi'  => round($norm['absensi'],  2),
            'sikap'    => round($norm['sikap'],     2),
            'disiplin' => round($norm['disiplin'],  2),
            'mu_nilai'    => round($mu['nilai'],    4),
            'mu_absensi'  => round($mu['absensi'],  4),
            'mu_sikap'    => round($mu['sikap'],    4),
            'mu_disiplin' => round($mu['disiplin'], 4),
            'skor'     => $skorFuzzy,
            'kategori' => $this->kategori($skorFuzzy),
            'ada_data' => $adaData,
        ];

        // ── Riwayat semua bulan (untuk tren mini) ─────────────────────────
        $riwayat = [];
        foreach (self::BULAN_MAP as $num => $bln) {
            $avgN = DB::table('nilais')
                ->where('siswa_id', $siswa->id)->whereIn('jadwal_id', $jadwalIds)
                ->where('bulan', $bln)->where('tahun_ajaran', $tahunStr)
                ->avg('nilai');

            $ttl = DB::table('absensi_details as d')
                ->join('absensis as a','a.id','=','d.absensi_id')
                ->where('d.siswa_id',$siswa->id)->whereIn('a.jadwal_id',$jadwalIds)
                ->where('a.bulan',$bln)->where('a.tahun_ajaran',$tahunStr)->count();

            $hdr = DB::table('absensi_details as d')
                ->join('absensis as a','a.id','=','d.absensi_id')
                ->where('d.siswa_id',$siswa->id)->where('d.status','hadir')
                ->whereIn('a.jadwal_id',$jadwalIds)
                ->where('a.bulan',$bln)->where('a.tahun_ajaran',$tahunStr)->count();

            $avgS = DB::table('sikaps')->where('siswa_id',$siswa->id)
                ->whereIn('jadwal_id',$jadwalIds)->where('bulan',$bln)
                ->where('tahun_ajaran',$tahunStr)->avg('nilai_sikap');

            $avgD = DB::table('kedisiplinans')->where('siswa_id',$siswa->id)
                ->whereIn('jadwal_id',$jadwalIds)->where('bulan',$bln)
                ->where('tahun_ajaran',$tahunStr)->avg('nilai_disiplin');

            $pHdr = $ttl > 0 ? round(($hdr/$ttl)*100,1) : null;

            $nN   = $this->clamp($avgN    ?? 0, 0, 100);
            $nA   = $this->clamp($pHdr    ?? 0, 0, 100);
            $nS   = $this->clamp($avgS    ?? 0, 0, 100);
            $nDis = $this->clamp($avgD    ?? 0, 0, 100);

            $skor = 0;
            if ($nN > 0 || $pHdr !== null || $nS > 0 || $nDis > 0) {
                $skor = round(
                    ($this->fuzzify($nN)*0.40 + $this->fuzzify($nA)*0.30
                   + $this->fuzzify($nS)*0.15 + $this->fuzzify($nDis)*0.15)*100, 1
                );
            }

            $riwayat[$bln] = [
                'bulan'    => $bln,
                'nilai'    => $avgN    !== null ? round($avgN, 1)   : null,
                'absensi'  => $pHdr,
                'sikap'    => $avgS    !== null ? round($avgS, 1)   : null,
                'disiplin' => $avgD    !== null ? round($avgD, 1)   : null,
                'skor'     => $skor,
                'kategori' => $skor > 0 ? $this->kategori($skor) : '—',
                'ada_data' => ($avgN !== null || $pHdr !== null || $avgS !== null || $avgD !== null),
            ];
        }

        // ── Bulan yang ada datanya untuk dropdown ──────────────────────────
        $bulanAda = collect($riwayat)->where('ada_data', true)->pluck('bulan');

        return view('orangtua.evaluasi-bulanan-anak', compact(
            'siswa', 'tahunAktif', 'bulanFilter', 'namaBulan',
            'hasil', 'nilaiPerMapel', 'absensiDetail', 'totalPertemuan',
            'riwayat', 'bulanAda'
        ));
    }

    // ─── Fuzzy trapesium ──────────────────────────────────────────────────────
    private function fuzzify(float $x): float
    {
        $muTinggi = $this->trapUp($x, 70, 80);
        $muSedang = $this->trapFull($x, 40, 55, 70, 80);
        $muRendah = $this->trapDown($x, 40, 55);
        $bobot    = $muTinggi + $muSedang + $muRendah;
        if ($bobot <= 0) return 0.0;
        return ($muTinggi * 100.0 + $muSedang * 67.5 + $muRendah * 27.5) / ($bobot * 100.0);
    }
    private function trapUp(float $x, float $a, float $b): float
    { return $x<=$a?0:($x>=$b?1:($x-$a)/($b-$a)); }
    private function trapDown(float $x, float $a, float $b): float
    { return $x<=$a?1:($x>=$b?0:($b-$x)/($b-$a)); }
    private function trapFull(float $x,float $a,float $b,float $c,float $d): float
    {
        if ($x<=$a||$x>=$d) return 0;
        if ($x>=$b&&$x<=$c) return 1;
        return $x<$b ? ($x-$a)/($b-$a) : ($d-$x)/($d-$c);
    }
    private function clamp(float $v, float $mn, float $mx): float
    { return max($mn, min($mx, $v)); }
    private function kategori(float $s): string
    {
        if ($s>=85) return 'Sangat Baik';
        if ($s>=70) return 'Baik';
        if ($s>=55) return 'Perlu Bimbingan';
        return 'Perlu Pembinaan';
    }
}
