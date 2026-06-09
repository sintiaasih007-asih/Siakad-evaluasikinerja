<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfileSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileSekolahController extends Controller
{
    /**
     * Tampilkan profil sekolah
     */
    public function index()
    {
        $profil = ProfileSekolah::firstOrCreate([]);

        return view(
            'admin.profil-sekolah.index',
            compact('profil')
        );
    }

    /**
     * Update profil sekolah
     */
    public function update(Request $request)
    {
        $profil = ProfileSekolah::firstOrCreate([]);

        $request->validate([
            'nama_sekolah'          => 'nullable|string|max:255',
            'npsn'                  => 'nullable|string|max:100',
            'nss'                   => 'nullable|string|max:100',
            'status_sekolah'        => 'nullable|string|max:100',
            'jenjang'               => 'nullable|string|max:100',
            'akreditasi'            => 'nullable|string|max:50',
            'izin_operasional'      => 'nullable|string|max:255',

            'nama_yayasan'          => 'nullable|string|max:255',

            'kepala_sekolah'        => 'nullable|string|max:255',
            'nip_kepala_sekolah'    => 'nullable|string|max:100',

            'telepon'               => 'nullable|string|max:50',
            'whatsapp'              => 'nullable|string|max:50',
            'email'                 => 'nullable|email|max:255',
            'website'               => 'nullable|max:255',

            'desa'                  => 'nullable|max:255',
            'kecamatan'             => 'nullable|max:255',
            'kabupaten'             => 'nullable|max:255',
            'provinsi'              => 'nullable|max:255',
            'kode_pos'              => 'nullable|max:20',

            'latitude'              => 'nullable|max:50',
            'longitude'             => 'nullable|max:50',

            'logo_sekolah'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'logo_yayasan'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_kepala_sekolah'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Upload Logo Sekolah
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('logo_sekolah')) {

            if (!empty($profil->logo_sekolah)) {
                Storage::disk('public')
                    ->delete($profil->logo_sekolah);
            }

            $profil->logo_sekolah = $request
                ->file('logo_sekolah')
                ->store(
                    'profil-sekolah/logo-sekolah',
                    'public'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Upload Logo Yayasan
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('logo_yayasan')) {

            if (!empty($profil->logo_yayasan)) {
                Storage::disk('public')
                    ->delete($profil->logo_yayasan);
            }

            $profil->logo_yayasan = $request
                ->file('logo_yayasan')
                ->store(
                    'profil-sekolah/logo-yayasan',
                    'public'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Upload Foto Kepala Sekolah
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('foto_kepala_sekolah')) {

            if (!empty($profil->foto_kepala_sekolah)) {
                Storage::disk('public')
                    ->delete($profil->foto_kepala_sekolah);
            }

            $profil->foto_kepala_sekolah = $request
                ->file('foto_kepala_sekolah')
                ->store(
                    'profil-sekolah/kepala-sekolah',
                    'public'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan Data Profil
        |--------------------------------------------------------------------------
        */
        $profil->fill([

            // Identitas Sekolah
            'nama_sekolah'          => $request->nama_sekolah,
            'npsn'                  => $request->npsn,
            'nss'                   => $request->nss,
            'status_sekolah'        => $request->status_sekolah,
            'jenjang'               => $request->jenjang,
            'akreditasi'            => $request->akreditasi,
            'izin_operasional'      => $request->izin_operasional,

            // Yayasan
            'nama_yayasan'          => $request->nama_yayasan,

            // Kepala Sekolah
            'kepala_sekolah'        => $request->kepala_sekolah,
            'nip_kepala_sekolah'    => $request->nip_kepala_sekolah,

            // Kontak
            'telepon'               => $request->telepon,
            'whatsapp'              => $request->whatsapp,
            'email'                 => $request->email,
            'website'               => $request->website,

            // Alamat
            'alamat'                => $request->alamat,
            'desa'                  => $request->desa,
            'kecamatan'             => $request->kecamatan,
            'kabupaten'             => $request->kabupaten,
            'provinsi'              => $request->provinsi,
            'kode_pos'              => $request->kode_pos,

            // Koordinat
            'latitude'              => $request->latitude,
            'longitude'             => $request->longitude,

            // Visi Misi
            'visi'                  => $request->visi,
            'misi'                  => $request->misi,
        ]);

        $profil->save();

        return redirect()
            ->route('profil-sekolah.index')
            ->with(
                'success',
                'Profil sekolah berhasil diperbarui.'
            );
    }
}