<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Siswa;

class PerkembanganAnakController extends Controller
{
    // Urutan bulan untuk sorting
    private const URUTAN_BULAN = [
        'Januari'=>1,'Februari'=>2,'Maret'=>3,'April'=>4,
        'Mei'=>5,'Juni'=>6,'Juli'=>7,'Agustus'=>8,
        'September'=>9,'Oktober'=>10,'November'=>11,'Desember'=>12,
    ];

    private const SEMUA_BULAN = [
        'Januari','Februari','Maret','April','Mei','Juni',
        'Juli','Agustus','September','Oktober','November','Desember',
    ];

    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user->siswa_id) {
            return view('orangtua.perkembangan-anak', ['siswa' => null]);
        }

        $siswa      = Siswa::with('kelas')->findOrFail($user->siswa_id);
        $tahunAktif = DB::table('tahun_ajarans')->where('is_active', 1)->first();
        $tahunStr   = $tahunAktif->tahun ?? '';

        // ── Filter semester (default: semester tahun ajaran aktif) ─────────
        $semesterFilter = $request->semester ?? ($tahunAktif->semester ?? 'genap');
        // semester di DB lowercase ('ganjil'/'genap'), tampilkan Titlecase
        $bulanSemester  = strtolower($semesterFilter) === 'ganjil'
            ? ['Juli','Agustus','September','Oktober','November','Desember']
            : ['Januari','Februari','Maret','April','Mei','Juni'];

        // ── Semua jadwal yang pernah ada nilainya untuk siswa ini ──────────
        $jadwalIds = DB::table('nilais')
            ->where('siswa_id', $siswa->id)
            ->where('tahun_ajaran', $tahunStr)
            ->distinct()
            ->pluck('jadwal_id');

        // Tambah jadwal dari absensi
        $jadwalAbsensi = DB::table('absensi_details as d')
            ->join('absensis as a', 'a.id', '=', 'd.absensi_id')
            ->where('d.siswa_id', $siswa->id)
            ->where('a.tahun_ajaran', $tahunStr)
            ->distinct()
            ->pluck('a.jadwal_id');

        $allJadwalIds = $jadwalIds->merge($jadwalAbsensi)->unique()->values();

        // ── Data per bulan (untuk chart dan tabel tren) ────────────────────
        $trendData = [];
        foreach ($bulanSemester as $bln) {

            // Nilai akademik rata-rata bulan ini (semua mapel)
            $avgNilai = DB::table('nilais')
                ->where('siswa_id', $siswa->id)
                ->where('tahun_ajaran', $tahunStr)
                ->where('bulan', $bln)
                ->avg('nilai') ?? null;

            // Absensi bulan ini
            $totalPertemuan = DB::table('absensi_details as d')
                ->join('absensis as a', 'a.id', '=', 'd.absensi_id')
                ->where('d.siswa_id', $siswa->id)
                ->where('a.tahun_ajaran', $tahunStr)
                ->where('a.bulan', $bln)
                ->count();

            $hadir = DB::table('absensi_details as d')
                ->join('absensis as a', 'a.id', '=', 'd.absensi_id')
                ->where('d.siswa_id', $siswa->id)
                ->where('d.status', 'hadir')
                ->where('a.tahun_ajaran', $tahunStr)
                ->where('a.bulan', $bln)
                ->count();

            $alpha = DB::table('absensi_details as d')
                ->join('absensis as a', 'a.id', '=', 'd.absensi_id')
                ->where('d.siswa_id', $siswa->id)
                ->where('d.status', 'alpha')
                ->where('a.tahun_ajaran', $tahunStr)
                ->where('a.bulan', $bln)
                ->count();

            $pctHadir = $totalPertemuan > 0
                ? round(($hadir / $totalPertemuan) * 100, 1)
                : null;

            // Sikap rata-rata bulan ini
            $avgSikap = DB::table('sikaps')
                ->where('siswa_id', $siswa->id)
                ->where('tahun_ajaran', $tahunStr)
                ->where('bulan', $bln)
                ->avg('nilai_sikap') ?? null;

            // Disiplin rata-rata bulan ini
            $avgDisiplin = DB::table('kedisiplinans')
                ->where('siswa_id', $siswa->id)
                ->where('tahun_ajaran', $tahunStr)
                ->where('bulan', $bln)
                ->avg('nilai_disiplin') ?? null;

            $adaData = ($avgNilai !== null || $totalPertemuan > 0
                     || $avgSikap !== null || $avgDisiplin !== null);

            $trendData[$bln] = [
                'bulan'       => $bln,
                'avg_nilai'   => $avgNilai   !== null ? round($avgNilai, 1)   : null,
                'pct_hadir'   => $pctHadir,
                'hadir'       => $hadir,
                'alpha'       => $alpha,
                'total'       => $totalPertemuan,
                'avg_sikap'   => $avgSikap   !== null ? round($avgSikap, 1)   : null,
                'avg_disiplin'=> $avgDisiplin!== null ? round($avgDisiplin, 1): null,
                'ada_data'    => $adaData,
            ];
        }

        // ── Statistik ringkasan semester ───────────────────────────────────
        $adaBulan     = collect($trendData)->where('ada_data', true);
        $nilaiValues  = $adaBulan->whereNotNull('avg_nilai')->pluck('avg_nilai');
        $hadirValues  = $adaBulan->whereNotNull('pct_hadir')->pluck('pct_hadir');
        $sikapValues  = $adaBulan->whereNotNull('avg_sikap')->pluck('avg_sikap');
        $disipValues  = $adaBulan->whereNotNull('avg_disiplin')->pluck('avg_disiplin');

        $ringkasan = [
            'avg_nilai'     => $nilaiValues->count()  ? round($nilaiValues->avg(), 1)  : 0,
            'avg_hadir'     => $hadirValues->count()  ? round($hadirValues->avg(), 1)  : 0,
            'avg_sikap'     => $sikapValues->count()  ? round($sikapValues->avg(), 1)  : 0,
            'avg_disiplin'  => $disipValues->count()  ? round($disipValues->avg(), 1)  : 0,
            'total_alpha'   => collect($trendData)->sum('alpha'),
            'bulan_ada'     => $adaBulan->count(),
            'nilai_terbaik' => $nilaiValues->count()  ? $nilaiValues->max()             : 0,
            'nilai_terendah'=> $nilaiValues->count()  ? $nilaiValues->min()             : 0,
            'trend_nilai'   => $this->hitungTrend($nilaiValues->values()->toArray()),
            'trend_hadir'   => $this->hitungTrend($hadirValues->values()->toArray()),
        ];

        // ── Nilai per mapel (detail semua bulan) ───────────────────────────
        $nilaiPerMapel = DB::table('nilais as n')
            ->join('jadwals as j', 'j.id', '=', 'n.jadwal_id')
            ->join('mapels as m',  'm.id', '=', 'j.mapel_id')
            ->where('n.siswa_id', $siswa->id)
            ->where('n.tahun_ajaran', $tahunStr)
            ->whereIn('n.bulan', $bulanSemester)
            ->select('m.nama_mapel', 'n.bulan', 'n.jenis_nilai', 'n.nilai', 'n.nama_penilaian')
            ->orderBy('m.nama_mapel')
            ->orderByRaw("FIELD(n.bulan,'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember')")
            ->get();

        // Group: mapel → avg per bulan
        $nilaiMapelSummary = [];
        foreach ($nilaiPerMapel as $n) {
            $nilaiMapelSummary[$n->nama_mapel]['bulan'][$n->bulan][] = $n->nilai;
        }
        foreach ($nilaiMapelSummary as $mapel => &$info) {
            $allNilai = [];
            foreach ($info['bulan'] as $bln => $vals) {
                $info['bulan'][$bln] = round(array_sum($vals) / count($vals), 1);
                $allNilai = array_merge($allNilai, $vals);
            }
            $info['rata'] = count($allNilai) ? round(array_sum($allNilai) / count($allNilai), 1) : 0;
        }
        unset($info);
        ksort($nilaiMapelSummary);

        // ── Absensi rekap total semester ───────────────────────────────────
        $absensiRekap = DB::table('absensi_details as d')
            ->join('absensis as a', 'a.id', '=', 'd.absensi_id')
            ->where('d.siswa_id', $siswa->id)
            ->where('a.tahun_ajaran', $tahunStr)
            ->whereIn('a.bulan', $bulanSemester)
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN d.status="hadir" THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN d.status="izin"  THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN d.status="sakit" THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN d.status="alpha" THEN 1 ELSE 0 END) as alpha
            ')
            ->first();

        // ── Data untuk chart (JSON) ────────────────────────────────────────
        $chartLabels  = array_map(fn($b) => substr($b, 0, 3), $bulanSemester); // Jan,Feb,...
        $chartNilai   = array_map(fn($b) => $trendData[$b]['avg_nilai'],    $bulanSemester);
        $chartHadir   = array_map(fn($b) => $trendData[$b]['pct_hadir'],    $bulanSemester);
        $chartSikap   = array_map(fn($b) => $trendData[$b]['avg_sikap'],    $bulanSemester);
        $chartDisiplin= array_map(fn($b) => $trendData[$b]['avg_disiplin'], $bulanSemester);

        return view('orangtua.perkembangan-anak', compact(
            'siswa', 'tahunAktif', 'semesterFilter', 'bulanSemester',
            'trendData', 'ringkasan', 'nilaiMapelSummary',
            'absensiRekap', 'chartLabels', 'chartNilai',
            'chartHadir', 'chartSikap', 'chartDisiplin'
        ));
    }

    /** Hitung tren: 'naik', 'turun', atau 'stabil' dari array nilai */
    private function hitungTrend(array $values): string
    {
        $ada = array_filter($values, fn($v) => $v !== null);
        if (count($ada) < 2) return 'stabil';
        $arr    = array_values($ada);
        $pertama = $arr[0];
        $terakhir= $arr[count($arr) - 1];
        if ($terakhir > $pertama + 2)  return 'naik';
        if ($terakhir < $pertama - 2)  return 'turun';
        return 'stabil';
    }
}
