<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RekapNilaiKelasController extends Controller
{
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
        $tahunAktif = DB::table('tahun_ajarans')
            ->where('is_active', 1)
            ->first();

        // ── Kelas binaan wali kelas ───────────────────────────────────────
        $kelas = null;
        if ($guruId) {
            $kelas = DB::table('kelas')
                ->where('guru_id', $guruId)
                ->first();
        }

        if (!$kelas || !$tahunAktif) {
            return view('walikelas.monitoring', [
                'kelas'      => $kelas,
                'tahunAktif' => $tahunAktif,
                'bulan'      => date('m'),
                'namaBulan'  => self::BULAN_MAP[date('m')] ?? '',
                'data'       => collect(),
                'mapelList'  => collect(),
                'bulanList'  => [],
                'ringkasan'  => [],
            ]);
        }

        $tahunStr = $tahunAktif->tahun;

        // ── Filter bulan (default bulan berjalan) ─────────────────────────
        $bulan     = $request->bulan ?? date('m');
        $namaBulan = self::BULAN_MAP[$bulan] ?? '';

        // ── Semua jadwal di kelas ini ─────────────────────────────────────
        $jadwals = DB::table('jadwals')
            ->join('mapels', 'mapels.id', '=', 'jadwals.mapel_id')
            ->join('gurus',  'gurus.id',  '=', 'jadwals.guru_id')
            ->where('jadwals.kelas_id', $kelas->id)
            ->select(
                'jadwals.id   as jadwal_id',
                'jadwals.guru_id',
                'mapels.id    as mapel_id',
                'mapels.nama_mapel',
                'gurus.nama   as nama_guru'
            )
            ->get()
            ->unique('jadwal_id');

        $jadwalIds = $jadwals->pluck('jadwal_id');
        $mapelList = $jadwals->unique('mapel_id')->values();

        // ── Semua bulan yang ada data nilai di kelas ini ──────────────────
        $bulanList = DB::table('nilais')
            ->whereIn('jadwal_id', $jadwalIds)
            ->where('tahun_ajaran', $tahunStr)
            ->select('bulan')
            ->distinct()
            ->orderByRaw("FIELD(bulan,'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember')")
            ->pluck('bulan')
            ->toArray();

        // Tambahkan bulan absensi yang mungkin berbeda
        $bulanAbsensi = DB::table('absensis')
            ->whereIn('jadwal_id', $jadwalIds)
            ->where('tahun_ajaran', $tahunStr)
            ->select('bulan')
            ->whereNotNull('bulan')
            ->distinct()
            ->pluck('bulan')
            ->toArray();

        $semuaBulan = array_unique(array_merge($bulanList, $bulanAbsensi));

        // ── Siswa kelas ini ───────────────────────────────────────────────
        $siswas = DB::table('siswas')
            ->where('kelas_id', $kelas->id)
            ->orderBy('nama')
            ->get();

        $data = collect();

        foreach ($siswas as $siswa) {

            // ── Nilai per mapel bulan ini ─────────────────────────────────
            $nilaiPerMapel = [];
            foreach ($mapelList as $mapel) {
                $jadwalMapelIds = $jadwals
                    ->where('mapel_id', $mapel->mapel_id)
                    ->pluck('jadwal_id');

                $avg = DB::table('nilais')
                    ->where('siswa_id', $siswa->id)
                    ->whereIn('jadwal_id', $jadwalMapelIds)
                    ->where('bulan', $namaBulan)
                    ->where('tahun_ajaran', $tahunStr)
                    ->avg('nilai');

                $nilaiPerMapel[$mapel->nama_mapel] = $avg !== null ? round($avg, 1) : null;
            }

            // ── Rata-rata nilai bulan ini (semua mapel) ───────────────────
            $nilaiAda  = array_filter($nilaiPerMapel, fn($v) => $v !== null);
            $rataMapel = count($nilaiAda) > 0
                ? round(array_sum($nilaiAda) / count($nilaiAda), 1)
                : 0;

            // ── Nilai kumulatif semester ini (semua bulan) ────────────────
            $nilaiKumulatif = DB::table('nilais')
                ->where('siswa_id', $siswa->id)
                ->whereIn('jadwal_id', $jadwalIds)
                ->where('tahun_ajaran', $tahunStr)
                ->avg('nilai') ?? 0;

            // ── Absensi bulan ini ─────────────────────────────────────────
            $totalBulanIni = DB::table('absensi_details as d')
                ->join('absensis as a', 'a.id', '=', 'd.absensi_id')
                ->where('d.siswa_id', $siswa->id)
                ->whereIn('a.jadwal_id', $jadwalIds)
                ->where('a.bulan', $namaBulan)
                ->where('a.tahun_ajaran', $tahunStr)
                ->count();

            $hadirBulanIni = DB::table('absensi_details as d')
                ->join('absensis as a', 'a.id', '=', 'd.absensi_id')
                ->where('d.siswa_id', $siswa->id)
                ->where('d.status', 'hadir')
                ->whereIn('a.jadwal_id', $jadwalIds)
                ->where('a.bulan', $namaBulan)
                ->where('a.tahun_ajaran', $tahunStr)
                ->count();

            $izinBulanIni = DB::table('absensi_details as d')
                ->join('absensis as a', 'a.id', '=', 'd.absensi_id')
                ->where('d.siswa_id', $siswa->id)
                ->where('d.status', 'izin')
                ->whereIn('a.jadwal_id', $jadwalIds)
                ->where('a.bulan', $namaBulan)
                ->where('a.tahun_ajaran', $tahunStr)
                ->count();

            $sakitBulanIni = DB::table('absensi_details as d')
                ->join('absensis as a', 'a.id', '=', 'd.absensi_id')
                ->where('d.siswa_id', $siswa->id)
                ->where('d.status', 'sakit')
                ->whereIn('a.jadwal_id', $jadwalIds)
                ->where('a.bulan', $namaBulan)
                ->where('a.tahun_ajaran', $tahunStr)
                ->count();

            $alphaBulanIni = DB::table('absensi_details as d')
                ->join('absensis as a', 'a.id', '=', 'd.absensi_id')
                ->where('d.siswa_id', $siswa->id)
                ->where('d.status', 'alpha')
                ->whereIn('a.jadwal_id', $jadwalIds)
                ->where('a.bulan', $namaBulan)
                ->where('a.tahun_ajaran', $tahunStr)
                ->count();

            $persenHadirBulan = $totalBulanIni > 0
                ? round(($hadirBulanIni / $totalBulanIni) * 100, 1)
                : 0;

            // ── Absensi kumulatif semester ────────────────────────────────
            $totalKumulatif = DB::table('absensi_details as d')
                ->join('absensis as a', 'a.id', '=', 'd.absensi_id')
                ->where('d.siswa_id', $siswa->id)
                ->whereIn('a.jadwal_id', $jadwalIds)
                ->where('a.tahun_ajaran', $tahunStr)
                ->count();

            $hadirKumulatif = DB::table('absensi_details as d')
                ->join('absensis as a', 'a.id', '=', 'd.absensi_id')
                ->where('d.siswa_id', $siswa->id)
                ->where('d.status', 'hadir')
                ->whereIn('a.jadwal_id', $jadwalIds)
                ->where('a.tahun_ajaran', $tahunStr)
                ->count();

            $alphaKumulatif = DB::table('absensi_details as d')
                ->join('absensis as a', 'a.id', '=', 'd.absensi_id')
                ->where('d.siswa_id', $siswa->id)
                ->where('d.status', 'alpha')
                ->whereIn('a.jadwal_id', $jadwalIds)
                ->where('a.tahun_ajaran', $tahunStr)
                ->count();

            $persenKumulatif = $totalKumulatif > 0
                ? round(($hadirKumulatif / $totalKumulatif) * 100, 1)
                : 0;

            // ── Sikap & Disiplin bulan ini ────────────────────────────────
            $sikapBulan = DB::table('sikaps')
                ->where('siswa_id', $siswa->id)
                ->whereIn('jadwal_id', $jadwalIds)
                ->where('bulan', $namaBulan)
                ->where('tahun_ajaran', $tahunStr)
                ->avg('nilai_sikap') ?? 0;

            $disiplinBulan = DB::table('kedisiplinans')
                ->where('siswa_id', $siswa->id)
                ->whereIn('jadwal_id', $jadwalIds)
                ->where('bulan', $namaBulan)
                ->where('tahun_ajaran', $tahunStr)
                ->avg('nilai_disiplin') ?? 0;

            // ── Status pantauan ───────────────────────────────────────────
            $status = $this->statusPantauan(
                $rataMapel,
                $persenHadirBulan,
                $alphaKumulatif
            );

            $data->push([
                'id'              => $siswa->id,
                'nis'             => $siswa->nis ?? '-',
                'nama'            => $siswa->nama,
                // bulan ini
                'nilai_bulan'     => $rataMapel,
                'nilai_per_mapel' => $nilaiPerMapel,
                'hadir_bulan'     => $hadirBulanIni,
                'izin_bulan'      => $izinBulanIni,
                'sakit_bulan'     => $sakitBulanIni,
                'alpha_bulan'     => $alphaBulanIni,
                'total_bulan'     => $totalBulanIni,
                'persen_hadir'    => $persenHadirBulan,
                'sikap'           => round($sikapBulan, 1),
                'disiplin'        => round($disiplinBulan, 1),
                // kumulatif
                'nilai_kumulatif' => round($nilaiKumulatif, 1),
                'persen_kumulatif'=> $persenKumulatif,
                'alpha_kumulatif' => $alphaKumulatif,
                // status
                'status'          => $status['label'],
                'status_color'    => $status['color'],
                'perlu_pantau'    => $status['pantau'],
            ]);
        }

        // ── Ringkasan kelas ───────────────────────────────────────────────
        $ringkasan = [
            'total_siswa'     => $data->count(),
            'avg_nilai'       => round($data->avg('nilai_bulan'), 1),
            'avg_hadir'       => round($data->avg('persen_hadir'), 1),
            'avg_kumulatif'   => round($data->avg('nilai_kumulatif'), 1),
            'perlu_pantau'    => $data->where('perlu_pantau', true)->count(),
            'sangat_baik'     => $data->where('status', 'Sangat Baik')->count(),
            'nilai_tertinggi' => $data->max('nilai_bulan') ?? 0,
            'nilai_terendah'  => $data->where('nilai_bulan', '>', 0)->min('nilai_bulan') ?? 0,
        ];

        return view('walikelas.monitoring', compact(
            'kelas', 'tahunAktif', 'bulan', 'namaBulan',
            'data', 'mapelList', 'semuaBulan', 'ringkasan'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Status pantauan berdasarkan nilai dan kehadiran
    // ─────────────────────────────────────────────────────────────────────────
    private function statusPantauan(float $nilai, float $hadir, int $alpha): array
    {
        if ($nilai >= 85 && $hadir >= 85) {
            return ['label' => 'Sangat Baik', 'color' => 'emerald', 'pantau' => false];
        }
        if ($nilai >= 75 && $hadir >= 75) {
            return ['label' => 'Baik',        'color' => 'blue',    'pantau' => false];
        }
        if ($alpha >= 3 || $hadir < 60) {
            return ['label' => 'Perlu Tindakan', 'color' => 'rose',  'pantau' => true];
        }
        if ($nilai >= 60 && $hadir >= 60) {
            return ['label' => 'Pantauan',    'color' => 'amber',   'pantau' => true];
        }
        return ['label' => 'Perlu Tindakan', 'color' => 'rose', 'pantau' => true];
    }
}
