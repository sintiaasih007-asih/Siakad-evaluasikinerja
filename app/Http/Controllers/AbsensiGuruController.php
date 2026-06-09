<?php

namespace App\Http\Controllers;

use App\Models\AbsensiGuru;
use App\Models\ProfileSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AbsensiGuruController extends Controller
{
    /**
     * Halaman Absensi Guru
     */
    public function index()
    {
        $guru = auth()->user()->guru;

        $absensiHariIni = AbsensiGuru::where(
            'guru_id',
            $guru->id
        )
        ->whereDate('tanggal', today())
        ->first();

        $riwayat = AbsensiGuru::where(
            'guru_id',
            $guru->id
        )
        ->latest()
        ->take(10)
        ->get();

        return view(
            'absensi-guru.index',
            compact(
                'guru',
                'absensiHariIni',
                'riwayat'
            )
        );
    }

    /**
     * Absen Masuk & Pulang
     */
    public function store(Request $request)
    {
        $guru = auth()->user()->guru;

        $today = now()->toDateString();

        $absensi = AbsensiGuru::where(
            'guru_id',
            $guru->id
        )
        ->whereDate('tanggal', $today)
        ->first();

        /**
         * ==========================
         * ABSEN MASUK
         * ==========================
         */
        if(
            $request->qr_token
            != cache('qr_absensi_harian')
        ){
            return back()->with(
                'error',
                'QR tidak valid'
            );
        }

        if (!$absensi)
        {
            $request->validate([
                'latitude'  => 'required',
                'longitude' => 'required',
            ]);


            /**
             * ==========================
             * VALIDASI GPS SEKOLAH
             * ==========================
             */

            $foto = null;

            if ($request->filled('foto_base64'))
            {
                $image = $request->foto_base64;

                $image = str_replace(
                    'data:image/png;base64,',
                    '',
                    $image
                );

                $image = str_replace(
                    ' ',
                    '+',
                    $image
                );

                $fileName =
                    'absensi-guru/' .
                    time() .
                    '.png';

                Storage::disk('public')
                    ->put(
                        $fileName,
                        base64_decode($image)
                    );

                $manager = new ImageManager(
                    new Driver()
                );

                $imageObject = $manager->read(
                    base64_decode($image)
                );

                $watermark =
                    now()->format('d-m-Y H:i:s')
                    . "\nLat : ".$request->latitude
                    . "\nLng : ".$request->longitude
                    . "\n".$request->alamat;

                $imageObject->text(
                    $watermark,
                    20,
                    20
                );

                $imageObject->save(
                    storage_path('app/public/'.$fileName)
                );

                $foto = $fileName;

                if(
                    !$request->face_verified
                )
                {
                    return back()->with(
                        'error',
                        'Verifikasi wajah gagal.'
                    );
                }
            }

            $profil = ProfileSekolah::first();

            if (
                $profil &&
                $profil->latitude &&
                $profil->longitude
            ) {

                $jarak = $this->hitungJarak(
                    $request->latitude,
                    $request->longitude,
                    $profil->latitude,
                    $profil->longitude
                );

                $radius = $profil->radius_absensi ?? 100;

                if ($jarak > $radius) {

                    return back()->with(
                        'error',
                        'Anda berada di luar radius sekolah (' .
                        round($jarak) .
                        ' meter)'
                    );
                }
            }

            if (!$absensi->face_verified) {
                return back()->with(
                    'error',
                    'Verifikasi wajah gagal.'
                );
            }

            // if ($request->qr_token != cache('qr_absensi_harian')) {
            //     return back()->with(
            //         'error',
            //         'QR Code tidak valid.'
            //     );
            // }

            // $token = cache(
            //     'qr_absensi_harian'
            // );

            /**
             * ==========================
             * BATAS JAM ABSEN MASUK
             * ==========================
             */
            if (now()->format('H:i') > '09:00') {

                return back()->with(
                    'error',
                    'Jam absensi masuk sudah ditutup. Silakan hubungi admin.'
                );
            }

            /**
             * ==========================
             * STATUS ABSENSI
             * ==========================
             */
            $status = now()->format('H:i') > '07:30'
                ? 'Terlambat'
                : 'Hadir';

            AbsensiGuru::create([

                'guru_id' => $guru->id,

                'tanggal' => $today,

                'jam_masuk' => now()->format('H:i:s'),

                'status' => $status,

                'latitude' => $request->latitude,

                'longitude' => $request->longitude,

                'alamat' => $request->alamat,

                'foto_absensi' => $foto,

                'face_verified' => true,

                'qr_token' => $request->qr_token,

            ]);

            return back()->with(
                'success',
                'Absen masuk berhasil.'
            );
        }

        /**
         * ==========================
         * ABSEN PULANG
         * ==========================
         */


        if (!$absensi->jam_pulang)
        {
            /**
             * ==========================
             * BATAS JAM ABSEN PULANG
             * ==========================
             */

            $jamPulangMinimal = '15:00';

            if (now()->format('H:i') < $jamPulangMinimal) {

                return back()->with(
                    'error',
                    'Absen pulang hanya bisa dilakukan setelah pukul '.$jamPulangMinimal
                );
            }


            $profil = ProfileSekolah::first();

            if (
                $profil &&
                $profil->latitude &&
                $profil->longitude &&
                $request->filled('latitude') &&
                $request->filled('longitude')
            ) {

                $jarak = $this->hitungJarak(
                    $request->latitude,
                    $request->longitude,
                    $profil->latitude,
                    $profil->longitude
                );

                $radius = $profil->radius_absensi ?? 100;

                if ($jarak > $radius) {

                    return back()->with(
                        'error',
                        'Anda berada di luar radius sekolah'
                    );
                }
            }

            $absensi->update([

                'jam_pulang' => now()->format('H:i:s'),

                'latitude' => $request->latitude,
                'longitude' => $request->longitude,

            ]);

            return back()->with(
                'success',
                'Absen pulang berhasil.'
            );
        }

        /**
         * ==========================
         * SUDAH ABSEN LENGKAP
         * ==========================
         */
        return back()->with(
            'error',
            'Absensi hari ini sudah lengkap.'
        );
    }

    /**
     * Hitung Jarak GPS
     */
    private function hitungJarak(
        $lat1,
        $lon1,
        $lat2,
        $lon2
    ){
        $earth = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a =
            sin($dLat / 2) * sin($dLat / 2)
            +
            cos(deg2rad($lat1))
            *
            cos(deg2rad($lat2))
            *
            sin($dLon / 2)
            *
            sin($dLon / 2);

        $c = 2 * atan2(
            sqrt($a),
            sqrt(1 - $a)
        );

        return $earth * $c;
    }
}