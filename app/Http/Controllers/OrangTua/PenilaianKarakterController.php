<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sikap;
use App\Models\Kedisiplinan;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class PenilaianKarakterController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // cek relasi siswa dari user
        if (!$user->siswa_id) {
            return view('orangtua.penilaian-karakter', [
                'siswa' => null,
                'sikaps' => collect(),
                'kedisiplinans' => collect(),
                'rataSikap' => 0,
                'rataDisiplin' => 0,
                'bulan' => null,
            ]);
        }

        $siswa = Siswa::find($user->siswa_id);

        $bulan = $request->bulan;

        // QUERY SIKAP
        $sikapsQuery = Sikap::where('siswa_id', $siswa->id);

        // QUERY KEDISIPLINAN
        $kedisiplinansQuery = Kedisiplinan::where('siswa_id', $siswa->id);

        // FILTER BULAN (jika dipilih)
        if ($bulan) {
            $sikapsQuery->where('bulan', $bulan);
            $kedisiplinansQuery->where('bulan', $bulan);
        }

        $sikaps = $sikapsQuery->latest()->get();
        $kedisiplinans = $kedisiplinansQuery->latest()->get();

        // RATA-RATA
        $rataSikap = round($sikaps->avg('nilai_sikap') ?? 0, 2);
        $rataDisiplin = round($kedisiplinans->avg('nilai_disiplin') ?? 0, 2);

        return view('orangtua.penilaian-karakter', compact(
            'siswa',
            'sikaps',
            'kedisiplinans',
            'rataSikap',
            'rataDisiplin',
            'bulan'
        ));
    }
}