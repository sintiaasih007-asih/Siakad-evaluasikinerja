<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Siswa;

class PenilaianKarakterController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user->siswa_id) {
            return view('orangtua.penilaian-karakter', [
                'siswa'          => null,
                'tahunAktif'     => null,
                'sikapGrouped'   => [],
                'disiplinGrouped'=> [],
                'rataSikap'      => 0,
                'rataDisiplin'   => 0,
                'mapelListSikap' => collect(),
                'mapelListDisiplin' => collect(),
                'bulanAda'       => collect(),
                'filterBulan'    => null,
                'ringkasan'      => [],
            ]);
        }

        $siswa      = Siswa::with('kelas')->findOrFail($user->siswa_id);
        $tahunAktif = DB::table('tahun_ajarans')->where('is_active', 1)->first();
        $tahunStr   = $tahunAktif->tahun ?? '';

        $filterBulan = $request->bulan ?? null;

        // ── SIKAP dengan kolom mapel ──────────────────────────────────────
        $sikapQuery = DB::table('sikaps as s')
            ->join('jadwals as j', 'j.id', '=', 's.jadwal_id')
            ->join('mapels as m',  'm.id', '=', 'j.mapel_id')
            ->join('gurus as g',   'g.id', '=', 's.guru_id')
            ->where('s.siswa_id',    $siswa->id)
            ->where('s.tahun_ajaran', $tahunStr)
            ->select(
                's.id', 's.nilai_sikap', 's.keterangan',
                's.bulan', 's.semester', 's.tanggal',
                'm.id as mapel_id', 'm.nama_mapel',
                'g.nama as nama_guru'
            );

        if ($filterBulan) $sikapQuery->where('s.bulan', $filterBulan);

        $sikapAll = $sikapQuery
            ->orderBy('m.nama_mapel')
            ->orderBy('s.tanggal')
            ->get();

        // Group sikap: mapel → daftar sikap
        $sikapGrouped = [];
        foreach ($sikapAll as $s) {
            $sikapGrouped[$s->nama_mapel][] = $s;
        }
        ksort($sikapGrouped);

        // ── KEDISIPLINAN dengan kolom mapel ───────────────────────────────
        $disiplinQuery = DB::table('kedisiplinans as k')
            ->join('jadwals as j', 'j.id', '=', 'k.jadwal_id')
            ->join('mapels as m',  'm.id', '=', 'j.mapel_id')
            ->join('gurus as g',   'g.id', '=', 'k.guru_id')
            ->where('k.siswa_id',    $siswa->id)
            ->where('k.tahun_ajaran', $tahunStr)
            ->select(
                'k.id', 'k.nilai_disiplin', 'k.keterangan',
                'k.bulan', 'k.semester', 'k.tanggal',
                'm.id as mapel_id', 'm.nama_mapel',
                'g.nama as nama_guru'
            );

        if ($filterBulan) $disiplinQuery->where('k.bulan', $filterBulan);

        $disiplinAll = $disiplinQuery
            ->orderBy('m.nama_mapel')
            ->orderBy('k.tanggal')
            ->get();

        // Group disiplin: mapel → daftar disiplin
        $disiplinGrouped = [];
        foreach ($disiplinAll as $d) {
            $disiplinGrouped[$d->nama_mapel][] = $d;
        }
        ksort($disiplinGrouped);

        // ── Statistik ─────────────────────────────────────────────────────
        $rataSikap    = round($sikapAll->avg('nilai_sikap')    ?? 0, 1);
        $rataDisiplin = round($disiplinAll->avg('nilai_disiplin') ?? 0, 1);

        $ringkasan = [
            'total_sikap'    => $sikapAll->count(),
            'total_disiplin' => $disiplinAll->count(),
            'rata_sikap'     => $rataSikap,
            'rata_disiplin'  => $rataDisiplin,
            'mapel_sikap'    => count($sikapGrouped),
            'mapel_disiplin' => count($disiplinGrouped),
        ];

        // Daftar mapel yang punya data sikap / disiplin
        $mapelListSikap = collect(array_keys($sikapGrouped));
        $mapelListDisiplin = collect(array_keys($disiplinGrouped));

        // Bulan yang ada data (gabungan sikap+disiplin)
        $bulanSikap = DB::table('sikaps')
            ->where('siswa_id', $siswa->id)
            ->where('tahun_ajaran', $tahunStr)
            ->whereNotNull('bulan')
            ->pluck('bulan');

        $bulanDisiplin = DB::table('kedisiplinans')
            ->where('siswa_id', $siswa->id)
            ->where('tahun_ajaran', $tahunStr)
            ->whereNotNull('bulan')
            ->pluck('bulan');

        $bulanAda = $bulanSikap->merge($bulanDisiplin)->unique()->sort()->values();

        return view('orangtua.penilaian-karakter', compact(
            'siswa', 'tahunAktif',
            'sikapGrouped', 'disiplinGrouped',
            'rataSikap', 'rataDisiplin',
            'mapelListSikap', 'mapelListDisiplin',
            'bulanAda', 'filterBulan', 'ringkasan'
        ));
    }
}
