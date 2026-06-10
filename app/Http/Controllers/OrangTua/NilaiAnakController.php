<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Siswa;

class NilaiAnakController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $siswa = Siswa::with('kelas')->findOrFail($user->siswa_id);

        $tahunAktif = DB::table('tahun_ajarans')->where('is_active', 1)->first();
        $tahunStr   = $tahunAktif->tahun ?? '';

        // Filter opsional
        $filterBulan     = $request->bulan     ?? null;
        $filterJenis     = $request->jenis     ?? null;
        $filterMapelId   = $request->mapel_id  ?? null;

        // ── Semua mapel yang pernah dinilai siswa ini ─────────────────────
        $mapelList = DB::table('nilais as n')
            ->join('jadwals as j', 'j.id', '=', 'n.jadwal_id')
            ->join('mapels as m',  'm.id', '=', 'j.mapel_id')
            ->where('n.siswa_id', $siswa->id)
            ->where('n.tahun_ajaran', $tahunStr)
            ->select('m.id as mapel_id', 'm.nama_mapel')
            ->distinct()
            ->orderBy('m.nama_mapel')
            ->get();

        // ── Ambil semua nilai dengan join ke mapel ────────────────────────
        $nilaiQuery = DB::table('nilais as n')
            ->join('jadwals as j', 'j.id', '=', 'n.jadwal_id')
            ->join('mapels as m',  'm.id', '=', 'j.mapel_id')
            ->join('gurus as g',   'g.id', '=', 'n.guru_id')
            ->where('n.siswa_id', $siswa->id)
            ->where('n.tahun_ajaran', $tahunStr)
            ->select(
                'n.id', 'n.jenis_nilai', 'n.nama_penilaian',
                'n.nilai', 'n.bulan', 'n.tanggal',
                'm.id as mapel_id', 'm.nama_mapel',
                'g.nama as nama_guru'
            );

        if ($filterBulan)   $nilaiQuery->where('n.bulan',   $filterBulan);
        if ($filterJenis)   $nilaiQuery->where('n.jenis_nilai', $filterJenis);
        if ($filterMapelId) $nilaiQuery->where('m.id',      $filterMapelId);

        $semualNilai = $nilaiQuery->orderBy('m.nama_mapel')->orderBy('n.tanggal')->get();

        // ── Group: jenis_nilai → mapel → daftar nilai ─────────────────────
        // Struktur: ['Tugas' => ['Matematika' => [...], 'B.Indonesia' => [...]], ...]
        $grouped = [];
        foreach ($semualNilai as $n) {
            $grouped[$n->jenis_nilai][$n->nama_mapel][] = $n;
        }
        ksort($grouped); // urut jenis A-Z

        // ── Statistik ringkasan ───────────────────────────────────────────
        $ringkasan = [
            'total'      => $semualNilai->count(),
            'rata'       => round($semualNilai->avg('nilai') ?? 0, 1),
            'tertinggi'  => $semualNilai->max('nilai') ?? 0,
            'terendah'   => $semualNilai->min('nilai') ?? 0,
        ];

        // Daftar bulan yang ada datanya
        $bulanAda = DB::table('nilais')
            ->where('siswa_id', $siswa->id)
            ->where('tahun_ajaran', $tahunStr)
            ->whereNotNull('bulan')
            ->select('bulan')->distinct()
            ->orderByRaw("FIELD(bulan,'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember')")
            ->pluck('bulan');

        $jenisAda = DB::table('nilais')
            ->where('siswa_id', $siswa->id)
            ->where('tahun_ajaran', $tahunStr)
            ->select('jenis_nilai')->distinct()
            ->pluck('jenis_nilai');

        return view('orangtua.nilai-anak', compact(
            'siswa', 'tahunAktif', 'grouped', 'ringkasan',
            'mapelList', 'bulanAda', 'jenisAda',
            'filterBulan', 'filterJenis', 'filterMapelId'
        ));
    }
}
