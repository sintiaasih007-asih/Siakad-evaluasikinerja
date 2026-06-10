<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbsensiGuru;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QrAbsensiGuruController extends Controller
{
    /**
     * Halaman kelola QR token absensi guru (khusus admin).
     */
    public function index()
    {
        $token      = cache('qr_absensi_harian');
        $totalHadir = AbsensiGuru::whereDate('tanggal', today())->count();

        return view('admin.qr-absensi-guru.index', compact('token', 'totalHadir'));
    }

    /**
     * Generate token QR baru untuk hari ini.
     */
    public function generate()
    {
        $token = Str::random(32);

        cache()->put(
            'qr_absensi_harian',
            $token,
            now()->endOfDay()
        );

        return back()->with('success', 'Token QR berhasil dibuat untuk hari ini.');
    }

    /**
     * Hapus / reset token QR aktif.
     */
    public function reset()
    {
        cache()->forget('qr_absensi_harian');

        return back()->with('success', 'Token QR berhasil direset.');
    }
}
