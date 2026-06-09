<?php

namespace App\Http\Controllers\Orangtua;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\AbsensiDetail;
use Illuminate\Support\Facades\Auth;

class AbsensiAnakController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user->siswa_id) {
            abort(403, 'Akun belum terhubung dengan data siswa.');
        }

        $siswa = Siswa::with('kelas')
            ->findOrFail($user->siswa_id);

        $absensi = AbsensiDetail::with([
            'absensi.jadwal.mapel',
            'absensi.jadwal.guru'
        ])
        ->where('siswa_id', $siswa->id)
        ->orderByDesc('created_at')
        ->get();

        return view('orangtua.absensi-anak', compact(
            'siswa',
            'absensi'
        ));
    }
}