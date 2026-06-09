<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Nilai;
use App\Models\Sikap;
use App\Models\Kedisiplinan;

class NilaiAnakController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $siswa = Siswa::with('kelas')
                    ->findOrFail($user->siswa_id);

        $nilai = Nilai::with('jadwal.mapel')
                    ->where('siswa_id', $siswa->id)
                    ->orderBy('tanggal', 'desc')
                    ->get();

        $sikap = Sikap::where('siswa_id', $siswa->id)
                    ->latest()
                    ->get();

        $disiplin = Kedisiplinan::where('siswa_id', $siswa->id)
                    ->latest()
                    ->get();

        return view('orangtua.nilai-anak', compact(
            'siswa',
            'nilai',
            'sikap',
            'disiplin'
        ));
    }
}
