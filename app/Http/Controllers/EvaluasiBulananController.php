<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EvaluasiBulananController extends Controller
{
    public function index(Request $request)
    {
        $guruId = Auth::user()->guru_id;

        // ambil tahun ajaran aktif
        $tahun = DB::table('tahun_ajarans')
            ->where('is_active', 1)
            ->first();

        // ambil jadwal guru
        $jadwals = DB::table('jadwals')
            ->join('mapels', 'mapels.id', '=', 'jadwals.mapel_id')
            ->join('kelas', 'kelas.id', '=', 'jadwals.kelas_id')
            ->where('jadwals.guru_id', $guruId)
            ->select(
                'jadwals.id',
                'mapels.nama_mapel',
                'kelas.nama_kelas'
            )
            ->get();

        // default jadwal
        $jadwalId = $request->jadwal_id ?? ($jadwals->first()->id ?? null);

        // bulan default
        $bulan = $request->bulan ?? date('m');

        // kelas list (SEMUA kelas tanpa filter error)
        $kelasList = DB::table('kelas')->get();

        // ambil siswa berdasarkan kelas
        $kelasId = $request->kelas_id;

        $siswas = DB::table('siswas')
            ->when($kelasId, function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            })
            ->get();

        $data = [];

        foreach ($siswas as $siswa) {

            $nilai = DB::table('nilais')
                ->where('siswa_id', $siswa->id)
                ->where('guru_id', $guruId)
                ->where('jadwal_id', $jadwalId)
                ->where('bulan', $this->namaBulan($bulan))
                ->where('tahun_ajaran', $tahun->tahun ?? '')
                ->avg('nilai') ?? 0;

            $absenTotal = DB::table('absensi_details as d')
                ->join('absensis as a', 'a.id', '=', 'd.absensi_id')
                ->where('d.siswa_id', $siswa->id)
                ->where('a.jadwal_id', $jadwalId)
                ->where('a.bulan', $this->namaBulan($bulan))
                ->where('a.tahun_ajaran', $tahun->tahun ?? '')
                ->count();

            $hadir = DB::table('absensi_details as d')
                ->join('absensis as a', 'a.id', '=', 'd.absensi_id')
                ->where('d.siswa_id', $siswa->id)
                ->where('d.status', 'hadir')
                ->where('a.jadwal_id', $jadwalId)
                ->where('a.bulan', $this->namaBulan($bulan))
                ->where('a.tahun_ajaran', $tahun->tahun ?? '')
                ->count();

            $absensi = $absenTotal > 0 ? round(($hadir / $absenTotal) * 100, 2) : 0;

            $sikap = DB::table('sikaps')
                ->where('siswa_id', $siswa->id)
                ->where('guru_id', $guruId)
                ->where('jadwal_id', $jadwalId)
                ->where('bulan', $this->namaBulan($bulan))
                ->where('tahun_ajaran', $tahun->tahun ?? '')
                ->avg('nilai_sikap') ?? 0;

            $disiplin = DB::table('kedisiplinans')
                ->where('siswa_id', $siswa->id)
                ->where('guru_id', $guruId)
                ->where('jadwal_id', $jadwalId)
                ->where('bulan', $this->namaBulan($bulan))
                ->where('tahun_ajaran', $tahun->tahun ?? '')
                ->avg('nilai_disiplin') ?? 0;

            // fuzzy sederhana
            $skor = ($nilai * 0.4) + ($absensi * 0.3) + ($sikap * 0.15) + ($disiplin * 0.15);

            $kategori = $this->kategori($skor);

            $data[] = [
                'nama' => $siswa->nama,
                'nilai' => round($nilai, 2),
                'absensi' => round($absensi, 2),
                'sikap' => round($sikap, 2),
                'disiplin' => round($disiplin, 2),
                'skor' => round($skor, 2),
                'kategori' => $kategori,
            ];
        }

        // sorting ranking
        usort($data, function ($a, $b) {
            return $b['skor'] <=> $a['skor'];
        });

        return view('evaluasi-bulanan.index', compact(
            'jadwals',
            'kelasList',
            'data',
            'tahun',
            'jadwalId',
            'bulan'
        ));
    }

    private function kategori($skor)
    {
        if ($skor >= 85) return 'Sangat Baik';
        if ($skor >= 75) return 'Baik';
        if ($skor >= 60) return 'Perlu Bimbingan';
        return 'Perlu Pembinaan';
    }

    private function namaBulan($bulan)
    {
        $map = [
            '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April',
            '05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus',
            '09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
        ];

        return $map[$bulan] ?? $bulan;
    }
}