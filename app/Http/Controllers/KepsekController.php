<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\FuzzyMamdaniService;

class KepsekController extends Controller
{
    // ─── helper bulan ─────────────────────────────────────────────────────────
    private const BULAN_MAP = [
        '01'=>'Januari',  '02'=>'Februari', '03'=>'Maret',    '04'=>'April',
        '05'=>'Mei',      '06'=>'Juni',     '07'=>'Juli',     '08'=>'Agustus',
        '09'=>'September','10'=>'Oktober',  '11'=>'November', '12'=>'Desember',
    ];

    // ─── tahun ajaran aktif ────────────────────────────────────────────────────
    private function tahunAktif(): ?object
    {
        return DB::table('tahun_ajarans')->where('is_active', 1)->first();
    }

    // ══════════════════════════════════════════════════════════════════════════
    // 1. LAPORAN AKADEMIK
    // ══════════════════════════════════════════════════════════════════════════
    public function laporanAkademik(Request $request)
    {
        $tahun    = $this->tahunAktif();
        $tahunStr = $tahun->tahun ?? '';

        // Filter
        $kelasId = $request->kelas_id;
        $bulan   = $request->bulan;

        $kelasList = DB::table('kelas')->orderBy('nama_kelas')->get();

        // Rekap per kelas
        $rekapKelas = DB::table('kelas as k')
            ->leftJoin('gurus as g', 'g.id', '=', 'k.guru_id')
            ->select('k.id', 'k.nama_kelas', 'g.nama as wali_kelas')
            ->orderBy('k.nama_kelas')
            ->get()
            ->map(function ($kls) use ($tahunStr, $bulan) {
                $jadwalIds = DB::table('jadwals')->where('kelas_id', $kls->id)->pluck('id');
                $jumlahSiswa = DB::table('siswas')->where('kelas_id', $kls->id)->count();

                $nilaiQ = DB::table('nilais')
                    ->whereIn('jadwal_id', $jadwalIds)
                    ->where('tahun_ajaran', $tahunStr);
                if ($bulan) $nilaiQ->where('bulan', self::BULAN_MAP[$bulan] ?? $bulan);
                $rataaNilai = $nilaiQ->avg('nilai') ?? 0;

                $absQ = DB::table('absensi_details as d')
                    ->join('absensis as a', 'a.id', '=', 'd.absensi_id')
                    ->whereIn('a.jadwal_id', $jadwalIds)
                    ->where('a.tahun_ajaran', $tahunStr);
                if ($bulan) $absQ->where('a.bulan', self::BULAN_MAP[$bulan] ?? $bulan);
                $totalAbs = $absQ->count();
                $hadirAbs = (clone $absQ)->where('d.status', 'hadir')->count();
                $pctHadir = $totalAbs > 0 ? round(($hadirAbs / $totalAbs) * 100, 1) : 0;

                $alphaAbs = (clone $absQ)->where('d.status', 'alpha')->count();

                return [
                    'id'          => $kls->id,
                    'nama_kelas'  => $kls->nama_kelas,
                    'wali_kelas'  => $kls->wali_kelas ?? '—',
                    'jml_siswa'   => $jumlahSiswa,
                    'rata_nilai'  => round($rataaNilai, 1),
                    'pct_hadir'   => $pctHadir,
                    'total_alpha' => $alphaAbs,
                ];
            });

        // Ringkasan keseluruhan
        $nilaiQ = DB::table('nilais')->where('tahun_ajaran', $tahunStr);
        if ($bulan) $nilaiQ->where('bulan', self::BULAN_MAP[$bulan] ?? $bulan);
        $globalNilai = round($nilaiQ->avg('nilai') ?? 0, 1);

        $absQ = DB::table('absensi_details as d')
            ->join('absensis as a', 'a.id', '=', 'd.absensi_id')
            ->where('a.tahun_ajaran', $tahunStr);
        if ($bulan) $absQ->where('a.bulan', self::BULAN_MAP[$bulan] ?? $bulan);
        $totalAbs   = $absQ->count();
        $hadirAbs   = (clone $absQ)->where('d.status', 'hadir')->count();
        $globalHadir = $totalAbs > 0 ? round(($hadirAbs / $totalAbs) * 100, 1) : 0;

        $ringkasan = [
            'total_siswa'  => DB::table('siswas')->count(),
            'total_kelas'  => DB::table('kelas')->count(),
            'total_guru'   => DB::table('gurus')->count(),
            'rata_nilai'   => $globalNilai,
            'pct_hadir'    => $globalHadir,
        ];

        // Nilai per mapel (top 10)
        $nilaiPerMapel = DB::table('nilais as n')
            ->join('jadwals as j', 'j.id', '=', 'n.jadwal_id')
            ->join('mapels as m', 'm.id', '=', 'j.mapel_id')
            ->where('n.tahun_ajaran', $tahunStr)
            ->when($bulan, fn($q) => $q->where('n.bulan', self::BULAN_MAP[$bulan] ?? $bulan))
            ->when($kelasId, fn($q) => $q->where('j.kelas_id', $kelasId))
            ->select('m.nama_mapel', DB::raw('AVG(n.nilai) as avg_nilai'), DB::raw('COUNT(*) as cnt'))
            ->groupBy('m.nama_mapel')
            ->orderByDesc('avg_nilai')
            ->limit(10)
            ->get();

        return view('kepsek.laporan-akademik', compact(
            'tahun', 'kelasList', 'rekapKelas', 'ringkasan',
            'nilaiPerMapel', 'kelasId', 'bulan'
        ));
    }

    // ══════════════════════════════════════════════════════════════════════════
    // 2. MONITORING GURU
    // ══════════════════════════════════════════════════════════════════════════
    public function monitoringGuru(Request $request)
    {
        $tahun     = $this->tahunAktif();
        $tahunStr  = $tahun->tahun ?? '';
        $bulan     = $request->bulan ?? date('m');
        $namaBulan = self::BULAN_MAP[$bulan] ?? '';

        $gurus = DB::table('gurus')->orderBy('nama')->get();

        $data = $gurus->map(function ($g) use ($tahunStr, $namaBulan, $bulan) {
            // Absensi guru bulan ini
            $absGuru = DB::table('absensi_gurus')
                ->where('guru_id', $g->id)
                ->whereYear('tanggal', date('Y'))
                ->whereMonth('tanggal', (int)$bulan)
                ->get();

            $hadirGuru    = $absGuru->whereIn('status', ['Hadir','Terlambat'])->count();
            $terlambat    = $absGuru->where('status', 'Terlambat')->count();
            $totalAbsGuru = $absGuru->count();
            $pctHadir     = $totalAbsGuru > 0 ? round(($hadirGuru / $totalAbsGuru) * 100, 1) : 0;

            // Jumlah kelas & nilai yang diinput bulan ini
            $jadwalIds = DB::table('jadwals')->where('guru_id', $g->id)->pluck('id');
            $nilaiCount = DB::table('nilais')
                ->where('guru_id', $g->id)
                ->where('bulan', $namaBulan)
                ->where('tahun_ajaran', $tahunStr)
                ->count();

            $absensiCount = DB::table('absensis')
                ->whereIn('jadwal_id', $jadwalIds)
                ->where('guru_id', $g->id)
                ->where('bulan', $namaBulan)
                ->where('tahun_ajaran', $tahunStr)
                ->count();

            $sikapCount = DB::table('sikaps')
                ->where('guru_id', $g->id)
                ->where('bulan', $namaBulan)
                ->where('tahun_ajaran', $tahunStr)
                ->count();

            // Status aktivitas
            $aktif = ($nilaiCount > 0 || $absensiCount > 0);
            $status = match(true) {
                $aktif && $pctHadir >= 90 => ['Aktif',     'emerald'],
                $aktif                   => ['Aktif',     'blue'],
                $terlambat > 3           => ['Perhatian', 'amber'],
                default                  => ['Kurang Aktif','rose'],
            };

            return [
                'id'            => $g->id,
                'nama'          => $g->nama,
                'nip'           => $g->nip,
                'hadir_guru'    => $hadirGuru,
                'terlambat'     => $terlambat,
                'total_abs'     => $totalAbsGuru,
                'pct_hadir'     => $pctHadir,
                'nilai_input'   => $nilaiCount,
                'absensi_buat'  => $absensiCount,
                'sikap_input'   => $sikapCount,
                'status'        => $status[0],
                'status_color'  => $status[1],
                'aktif'         => $aktif,
            ];
        });

        $ringkasan = [
            'total_guru'    => $gurus->count(),
            'guru_aktif'    => $data->where('aktif', true)->count(),
            'rata_hadir'    => round($data->avg('pct_hadir'), 1),
            'perlu_perhatian'=> $data->whereIn('status', ['Perhatian','Kurang Aktif'])->count(),
        ];

        return view('kepsek.monitoring-guru', compact(
            'tahun', 'data', 'ringkasan', 'bulan', 'namaBulan'
        ));
    }

    // ══════════════════════════════════════════════════════════════════════════
    // 3. MONITORING SISWA
    // ══════════════════════════════════════════════════════════════════════════
    public function monitoringSiswa(Request $request)
    {
        $tahun    = $this->tahunAktif();
        $tahunStr = $tahun->tahun ?? '';
        $bulan    = $request->bulan ?? date('m');
        $kelasId  = $request->kelas_id;
        $namaBulan = self::BULAN_MAP[$bulan] ?? '';

        $kelasList = DB::table('kelas')->orderBy('nama_kelas')->get();

        // Filter kelas
        $siswaQuery = DB::table('siswas as s')
            ->join('kelas as k', 'k.id', '=', 's.kelas_id')
            ->select('s.id', 's.nama', 's.nis', 'k.nama_kelas', 's.kelas_id');
        if ($kelasId) $siswaQuery->where('s.kelas_id', $kelasId);
        $siswas = $siswaQuery->orderBy('k.nama_kelas')->orderBy('s.nama')->get();

        $data = $siswas->map(function ($s) use ($tahunStr, $namaBulan) {
            $jadwalIds = DB::table('jadwals')->where('kelas_id', $s->kelas_id)->pluck('id');

            // Nilai
            $avgNilai = DB::table('nilais')
                ->where('siswa_id', $s->id)
                ->whereIn('jadwal_id', $jadwalIds)
                ->where('bulan', $namaBulan)
                ->where('tahun_ajaran', $tahunStr)
                ->avg('nilai') ?? 0;

            // Absensi
            $total = DB::table('absensi_details as d')
                ->join('absensis as a','a.id','=','d.absensi_id')
                ->where('d.siswa_id', $s->id)
                ->whereIn('a.jadwal_id', $jadwalIds)
                ->where('a.bulan', $namaBulan)
                ->where('a.tahun_ajaran', $tahunStr)
                ->count();
            $hadir = DB::table('absensi_details as d')
                ->join('absensis as a','a.id','=','d.absensi_id')
                ->where('d.siswa_id', $s->id)->where('d.status','hadir')
                ->whereIn('a.jadwal_id', $jadwalIds)
                ->where('a.bulan', $namaBulan)
                ->where('a.tahun_ajaran', $tahunStr)
                ->count();
            $alpha = DB::table('absensi_details as d')
                ->join('absensis as a','a.id','=','d.absensi_id')
                ->where('d.siswa_id', $s->id)->where('d.status','alpha')
                ->whereIn('a.jadwal_id', $jadwalIds)
                ->where('a.bulan', $namaBulan)
                ->where('a.tahun_ajaran', $tahunStr)
                ->count();
            $pctHadir = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;

            // Status pantauan
            $status = match(true) {
                $avgNilai >= 80 && $pctHadir >= 85 => ['Baik',           'emerald'],
                $alpha >= 3 || $pctHadir < 60      => ['Perlu Tindakan', 'rose'],
                $avgNilai >= 65 && $pctHadir >= 70 => ['Stabil',         'blue'],
                default                             => ['Pantauan',       'amber'],
            };

            return [
                'nama'        => $s->nama,
                'nis'         => $s->nis,
                'nama_kelas'  => $s->nama_kelas,
                'avg_nilai'   => round($avgNilai, 1),
                'pct_hadir'   => $pctHadir,
                'alpha'       => $alpha,
                'status'      => $status[0],
                'status_color'=> $status[1],
                'perlu_aksi'  => $status[1] === 'rose' || $status[1] === 'amber',
            ];
        });

        $ringkasan = [
            'total_siswa'    => $siswas->count(),
            'status_baik'    => $data->where('status', 'Baik')->count(),
            'perlu_tindakan' => $data->where('status', 'Perlu Tindakan')->count(),
            'pantauan'       => $data->where('status', 'Pantauan')->count(),
            'rata_nilai'     => round($data->avg('avg_nilai'), 1),
            'rata_hadir'     => round($data->avg('pct_hadir'), 1),
        ];

        return view('kepsek.monitoring-siswa', compact(
            'tahun', 'kelasList', 'data', 'ringkasan',
            'bulan', 'namaBulan', 'kelasId'
        ));
    }

    // ══════════════════════════════════════════════════════════════════════════
    // 4. HASIL EVALUASI SISWA (Fuzzy Mamdani)
    // ══════════════════════════════════════════════════════════════════════════
    public function hasilEvaluasi(Request $request)
    {
        $tahun     = $this->tahunAktif();
        $tahunStr  = $tahun->tahun ?? '';
        $semester  = $request->semester ?? ($tahun->semester ?? 'genap');
        $kelasId   = $request->kelas_id;
        $kelasList = DB::table('kelas')->orderBy('nama_kelas')->get();

        $bulanList = strtolower($semester) === 'ganjil'
            ? ['Juli','Agustus','September','Oktober','November','Desember']
            : ['Januari','Februari','Maret','April','Mei','Juni'];

        $fuzzy = app(FuzzyMamdaniService::class);

        // Query siswa
        $siswaQ = DB::table('siswas as s')
            ->join('kelas as k','k.id','=','s.kelas_id')
            ->select('s.id','s.nama','s.nis','k.nama_kelas','s.kelas_id');
        if ($kelasId) $siswaQ->where('s.kelas_id', $kelasId);
        $siswas = $siswaQ->orderBy('k.nama_kelas')->orderBy('s.nama')->get();

        $data = $siswas->map(function ($s) use ($tahunStr, $bulanList, $fuzzy) {
            $jadwalIds = DB::table('jadwals')->where('kelas_id', $s->kelas_id)->pluck('id');
            $pembagi   = max(1, count($bulanList));

            // Akumulasi per semester
            $sumNilai = $sumHadir = $sumSikap = $sumDisiplin = 0;
            $totalAlpha = 0;

            foreach ($bulanList as $bln) {
                $sumNilai += DB::table('nilais')
                    ->where('siswa_id',$s->id)->whereIn('jadwal_id',$jadwalIds)
                    ->where('bulan',$bln)->where('tahun_ajaran',$tahunStr)
                    ->avg('nilai') ?? 0;

                $ttl = DB::table('absensi_details as d')
                    ->join('absensis as a','a.id','=','d.absensi_id')
                    ->where('d.siswa_id',$s->id)->whereIn('a.jadwal_id',$jadwalIds)
                    ->where('a.bulan',$bln)->where('a.tahun_ajaran',$tahunStr)->count();
                $hdr = DB::table('absensi_details as d')
                    ->join('absensis as a','a.id','=','d.absensi_id')
                    ->where('d.siswa_id',$s->id)->where('d.status','hadir')
                    ->whereIn('a.jadwal_id',$jadwalIds)
                    ->where('a.bulan',$bln)->where('a.tahun_ajaran',$tahunStr)->count();
                $alp = DB::table('absensi_details as d')
                    ->join('absensis as a','a.id','=','d.absensi_id')
                    ->where('d.siswa_id',$s->id)->where('d.status','alpha')
                    ->whereIn('a.jadwal_id',$jadwalIds)
                    ->where('a.bulan',$bln)->where('a.tahun_ajaran',$tahunStr)->count();
                $sumHadir += $ttl > 0 ? round(($hdr/$ttl)*100, 2) : 0;
                $totalAlpha += $alp;

                $sumSikap += DB::table('sikaps')
                    ->where('siswa_id',$s->id)->whereIn('jadwal_id',$jadwalIds)
                    ->where('bulan',$bln)->where('tahun_ajaran',$tahunStr)
                    ->avg('nilai_sikap') ?? 0;

                $sumDisiplin += DB::table('kedisiplinans')
                    ->where('siswa_id',$s->id)->whereIn('jadwal_id',$jadwalIds)
                    ->where('bulan',$bln)->where('tahun_ajaran',$tahunStr)
                    ->avg('nilai_disiplin') ?? 0;
            }

            $rNilai   = $sumNilai   / $pembagi;
            $rHadir   = $sumHadir   / $pembagi;
            $rSikap   = $sumSikap   / $pembagi;
            $rDisiplin= $sumDisiplin/ $pembagi;

            $hasil = $fuzzy->hitung($rNilai, $rHadir, $rSikap, $rDisiplin);

            return [
                'nama'        => $s->nama,
                'nis'         => $s->nis,
                'nama_kelas'  => $s->nama_kelas,
                'nilai'       => round($rNilai, 1),
                'hadir'       => round($rHadir, 1),
                'sikap'       => round($rSikap, 1),
                'disiplin'    => round($rDisiplin, 1),
                'total_alpha' => $totalAlpha,
                'skor'        => $hasil['skor'],
                'kategori'    => $hasil['kategori'],
                'ada_data'    => ($rNilai > 0 || $rHadir > 0),
            ];
        })->sortByDesc('skor')->values();

        $distrib = $data->groupBy('kategori')->map->count();
        $ringkasan = [
            'total'           => $data->count(),
            'sangat_baik'     => $distrib['Sangat Baik'] ?? 0,
            'baik'            => $distrib['Baik'] ?? 0,
            'perlu_bimbingan' => $distrib['Perlu Bimbingan'] ?? 0,
            'perlu_pembinaan' => $distrib['Perlu Pembinaan'] ?? 0,
            'rata_skor'       => round($data->avg('skor'), 1),
        ];

        return view('kepsek.hasil-evaluasi', compact(
            'tahun', 'kelasList', 'data', 'ringkasan',
            'semester', 'kelasId', 'bulanList'
        ));
    }
}
