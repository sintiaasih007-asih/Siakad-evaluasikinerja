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
        $profil          = ProfileSekolah::first();
        $evaluasiAktif   = $profil?->evaluasi_bulanan_aktif ?? false;

        // Jika user adalah admin, selalu bisa akses
        if ($user->role === 'admin') {
            $evaluasiAktif = true;
        }

        // Tahun ajaran aktif
        $tahun = DB::table('tahun_ajarans')
            ->where('is_active', 1)
            ->first();

        // Jadwal milik guru yang login (mapel + kelas unik)
        $jadwals = DB::table('jadwals')
            ->join('mapels', 'mapels.id', '=', 'jadwals.mapel_id')
            ->join('kelas',  'kelas.id',  '=', 'jadwals.kelas_id')
            ->where('jadwals.guru_id', $guruId)
            ->select(
                'jadwals.id',
                'jadwals.kelas_id',
                'mapels.nama_mapel',
                'kelas.nama_kelas'
            )
            ->get()
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

            // Pastikan jadwal yang dipilih memang milik guru ini
            $jadwalValid = DB::table('jadwals')
                ->where('id', $jadwalId)
                ->where('guru_id', $guruId)
                ->exists();

            // Pastikan kelas yang dipilih termasuk kelas yang diampu
            $kelasValid = $kelasIds->contains($kelasId);

            if ($jadwalValid && $kelasValid) {

                $siswas = DB::table('siswas')
                    ->where('kelas_id', $kelasId)
                    ->orderBy('nama')
                    ->get();

                $namaBulan = $this->namaBulan($bulan);

                foreach ($siswas as $siswa) {

                    $nilai = DB::table('nilais')
                        ->where('siswa_id', $siswa->id)
                        ->where('guru_id', $guruId)
                        ->where('jadwal_id', $jadwalId)
                        ->where('bulan', $namaBulan)
                        ->where('tahun_ajaran', $tahun->tahun ?? '')
                        ->avg('nilai') ?? 0;

                    $absenTotal = DB::table('absensi_details as d')
                        ->join('absensis as a', 'a.id', '=', 'd.absensi_id')
                        ->where('d.siswa_id', $siswa->id)
                        ->where('a.jadwal_id', $jadwalId)
                        ->where('a.bulan', $namaBulan)
                        ->where('a.tahun_ajaran', $tahun->tahun ?? '')
                        ->count();

                    $hadir = DB::table('absensi_details as d')
                        ->join('absensis as a', 'a.id', '=', 'd.absensi_id')
                        ->where('d.siswa_id', $siswa->id)
                        ->where('d.status', 'hadir')
                        ->where('a.jadwal_id', $jadwalId)
                        ->where('a.bulan', $namaBulan)
                        ->where('a.tahun_ajaran', $tahun->tahun ?? '')
                        ->count();

                    $absensi = $absenTotal > 0
                        ? round(($hadir / $absenTotal) * 100, 2)
                        : 0;

                    $sikap = DB::table('sikaps')
                        ->where('siswa_id', $siswa->id)
                        ->where('guru_id', $guruId)
                        ->where('jadwal_id', $jadwalId)
                        ->where('bulan', $namaBulan)
                        ->where('tahun_ajaran', $tahun->tahun ?? '')
                        ->avg('nilai_sikap') ?? 0;

                    $disiplin = DB::table('kedisiplinans')
                        ->where('siswa_id', $siswa->id)
                        ->where('guru_id', $guruId)
                        ->where('jadwal_id', $jadwalId)
                        ->where('bulan', $namaBulan)
                        ->where('tahun_ajaran', $tahun->tahun ?? '')
                        ->avg('nilai_disiplin') ?? 0;

                    $skor = ($nilai * 0.4) + ($absensi * 0.3) + ($sikap * 0.15) + ($disiplin * 0.15);

                    $data[] = [
                        'nama'     => $siswa->nama,
                        'nilai'    => round($nilai, 2),
                        'absensi'  => round($absensi, 2),
                        'sikap'    => round($sikap, 2),
                        'disiplin' => round($disiplin, 2),
                        'skor'     => round($skor, 2),
                        'kategori' => $this->kategori($skor),
                    ];
                }

                // Urutkan berdasarkan skor
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

    private function kategori(float $skor): string
    {
        if ($skor >= 85) return 'Sangat Baik';
        if ($skor >= 75) return 'Baik';
        if ($skor >= 60) return 'Perlu Bimbingan';
        return 'Perlu Pembinaan';
    }

    private function namaBulan(string $bulan): string
    {
        return [
            '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April',
            '05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus',
            '09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
        ][$bulan] ?? $bulan;
    }
}
