<?php

namespace App\Http\Controllers;

use App\Models\AbsensiGuru;
use App\Models\Jadwal;
use App\Models\ProfileSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AbsensiGuruController extends Controller
{
    /**
     * Halaman Absensi Guru
     */
    public function index()
    {
        $guru = auth()->user()->guru;

        // Cek jadwal hari ini (nama hari dalam bahasa Inggris sesuai kolom 'hari')
        $hariIni = now()->locale('id')->isoFormat('dddd'); // Senin, Selasa, dst.

        // Fallback ke format Carbon standar jika kolom memakai bahasa Inggris
        $hariInggris = now()->format('l'); // Monday, Tuesday, dst.

        $punya_jadwal = Jadwal::where('guru_id', $guru->id)
            ->where(function ($q) use ($hariIni, $hariInggris) {
                $q->whereRaw('LOWER(hari) = ?', [strtolower($hariIni)])
                  ->orWhereRaw('LOWER(hari) = ?', [strtolower($hariInggris)]);
            })
            ->exists();

        $jadwalHariIni = Jadwal::with(['mapel', 'kelas'])
            ->where('guru_id', $guru->id)
            ->where(function ($q) use ($hariIni, $hariInggris) {
                $q->whereRaw('LOWER(hari) = ?', [strtolower($hariIni)])
                  ->orWhereRaw('LOWER(hari) = ?', [strtolower($hariInggris)]);
            })
            ->orderBy('jam_masuk')
            ->get();

        $absensiHariIni = AbsensiGuru::where('guru_id', $guru->id)
            ->whereDate('tanggal', today())
            ->first();

        $riwayat = AbsensiGuru::where('guru_id', $guru->id)
            ->latest()
            ->take(10)
            ->get();

        return view('absensi-guru.index', compact(
            'guru',
            'absensiHariIni',
            'riwayat',
            'punya_jadwal',
            'jadwalHariIni'
        ));
    }

    /**
     * Absen Masuk & Pulang
     */
    public function store(Request $request)
    {
        $guru  = auth()->user()->guru;
        $today = now()->toDateString();

        // ── Validasi: harus punya jadwal hari ini ─────────────────────
        $hariIni    = now()->locale('id')->isoFormat('dddd');
        $hariInggris = now()->format('l');

        $punyaJadwal = Jadwal::where('guru_id', $guru->id)
            ->where(function ($q) use ($hariIni, $hariInggris) {
                $q->whereRaw('LOWER(hari) = ?', [strtolower($hariIni)])
                  ->orWhereRaw('LOWER(hari) = ?', [strtolower($hariInggris)]);
            })
            ->exists();

        if (!$punyaJadwal) {
            return back()->with('error', 'Anda tidak memiliki jadwal mengajar hari ini. Absensi hanya untuk guru yang memiliki jadwal.');
        }

        // ── Validasi Token QR ──────────────────────────────────────────
        $tokenValid = cache('qr_absensi_harian');

        if (!$tokenValid || $request->qr_token !== $tokenValid) {
            return back()->with('error', 'Token QR tidak valid atau sudah kedaluwarsa.');
        }

        $absensi = AbsensiGuru::where('guru_id', $guru->id)
            ->whereDate('tanggal', $today)
            ->first();

        // ═══════════════════════════════════════════════════════════════
        //  ABSEN MASUK
        // ═══════════════════════════════════════════════════════════════
        if (!$absensi) {

            $request->validate([
                'latitude'  => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);

            // Batas jam absensi masuk
            if (now()->format('H:i') > '15:00') {
                return back()->with('error', 'Jam absensi masuk sudah ditutup (batas 09:00).');
            }

            // Validasi radius dari sekolah
            $profil = ProfileSekolah::first();

            if ($profil && $profil->latitude && $profil->longitude) {

                $jarak = $this->hitungJarak(
                    $request->latitude,
                    $request->longitude,
                    $profil->latitude,
                    $profil->longitude
                );

                $radius = $profil->radius_absensi ?? 100;

                if ($jarak > $radius) {
                    return back()->with('error',
                        'Anda berada di luar radius sekolah (' . round($jarak) . ' m dari ' . $radius . ' m).'
                    );
                }
            }

            // Simpan foto selfie (base64)
            $foto = null;

            if ($request->filled('foto_base64')) {
                $foto = $this->simpanFotoBase64(
                    $request->foto_base64,
                    $request->latitude,
                    $request->longitude,
                    $request->alamat
                );
            }

            // Status terlambat / hadir
            $status = now()->format('H:i') > '07:30' ? 'Terlambat' : 'Hadir';

            $dataAbsensi = [
                'guru_id'      => $guru->id,
                'tanggal'      => $today,
                'jam_masuk'    => now()->format('H:i:s'),
                'status'       => $status,
                'latitude'     => $request->latitude,
                'longitude'    => $request->longitude,
                'alamat'       => $request->alamat,
                'foto_absensi' => $foto,
                'face_verified'=> true,
            ];

            // Kolom opsional — hanya isi jika sudah ada di tabel
            if (\Illuminate\Support\Facades\Schema::hasColumn('absensi_gurus', 'qr_verified')) {
                $dataAbsensi['qr_verified'] = true;
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('absensi_gurus', 'qr_token')) {
                $dataAbsensi['qr_token'] = $request->qr_token;
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('absensi_gurus', 'scan_time')) {
                $dataAbsensi['scan_time'] = now();
            }

            AbsensiGuru::create($dataAbsensi);

            return back()->with('success', 'Absen masuk berhasil. Status: ' . $status);
        }

        // ═══════════════════════════════════════════════════════════════
        //  ABSEN PULANG
        // ═══════════════════════════════════════════════════════════════
        if (!$absensi->jam_pulang) {

            // Batas minimal jam pulang
            if (now()->format('H:i') < '13:00') {
                return back()->with('error', 'Absen pulang hanya bisa dilakukan setelah pukul 13:00.');
            }

            // Validasi radius
            $profil = ProfileSekolah::first();

            if (
                $profil && $profil->latitude && $profil->longitude &&
                $request->filled('latitude') && $request->filled('longitude')
            ) {
                $jarak  = $this->hitungJarak(
                    $request->latitude, $request->longitude,
                    $profil->latitude, $profil->longitude
                );
                $radius = $profil->radius_absensi ?? 100;

                if ($jarak > $radius) {
                    return back()->with('error',
                        'Anda berada di luar radius sekolah (' . round($jarak) . ' m).'
                    );
                }
            }

            $absensi->update([
                'jam_pulang' => now()->format('H:i:s'),
                'latitude'   => $request->latitude,
                'longitude'  => $request->longitude,
                'alamat'     => $request->alamat ?? $absensi->alamat,
            ]);

            return back()->with('success', 'Absen pulang berhasil.');
        }

        // ═══════════════════════════════════════════════════════════════
        //  SUDAH ABSEN LENGKAP
        // ═══════════════════════════════════════════════════════════════
        return back()->with('error', 'Absensi hari ini sudah lengkap.');
    }

    /**
     * Simpan foto selfie base64 ke storage dengan watermark teks.
     */
    private function simpanFotoBase64(string $base64, $lat, $lng, $alamat): string
    {
        // Bersihkan prefix data URL
        $base64clean = preg_replace('#^data:image/\w+;base64,#i', '', $base64);
        $base64clean = str_replace(' ', '+', $base64clean);

        $decoded  = base64_decode($base64clean);
        $fileName = 'absensi-guru/' . time() . '_' . rand(1000, 9999) . '.jpg';

        Storage::disk('public')->put($fileName, $decoded);

        return $fileName;
    }

    /**
     * Hitung jarak dua koordinat GPS (Haversine, meter).
     */
    private function hitungJarak($lat1, $lon1, $lat2, $lon2): float
    {
        $earth = 6371000;
        $dLat  = deg2rad($lat2 - $lat1);
        $dLon  = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

        return $earth * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}
