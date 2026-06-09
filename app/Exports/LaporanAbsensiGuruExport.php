<?php

namespace App\Exports;

use App\Models\AbsensiGuru;
use App\Models\ProfileSekolah;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class LaporanAbsensiGuruExport implements FromView, WithDrawings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $query = AbsensiGuru::with('guru');

        if ($this->request->filled('guru')) {
            $query->where('guru_id', $this->request->guru);
        }

        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        if ($this->request->filled('tanggal_awal')) {
            $query->whereDate('tanggal', '>=', $this->request->tanggal_awal);
        }

        if ($this->request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal', '<=', $this->request->tanggal_akhir);
        }

        return view(
            'admin.laporan-absensi-guru.excel',
            [
                'profil' => ProfileSekolah::first(),

                'absensi' => (clone $query)->get(),

                'hadir' => (clone $query)
                    ->where('status', 'Hadir')
                    ->count(),

                'terlambat' => (clone $query)
                    ->where('status', 'Terlambat')
                    ->count(),

                'izin' => (clone $query)
                    ->where('status', 'Izin')
                    ->count(),

                'sakit' => (clone $query)
                    ->where('status', 'Sakit')
                    ->count(),

                'alpa' => (clone $query)
                    ->where('status', 'Alpa')
                    ->count(),
            ]
        );
    }

    public function drawings()
    {
        $profil = ProfileSekolah::first();

        $drawings = [];

        if ($profil?->logo_sekolah) {

            $logoSekolah = new Drawing();

            $logoSekolah->setName('Logo Sekolah');

            $logoSekolah->setPath(
                public_path('storage/' . $profil->logo_sekolah)
            );

            $logoSekolah->setHeight(70);

            $logoSekolah->setCoordinates('A1');

            $drawings[] = $logoSekolah;
        }

        if ($profil?->logo_yayasan) {

            $logoYayasan = new Drawing();

            $logoYayasan->setName('Logo Yayasan');

            $logoYayasan->setPath(
                public_path('storage/' . $profil->logo_yayasan)
            );

            $logoYayasan->setHeight(70);

            $logoYayasan->setCoordinates('G1');

            $drawings[] = $logoYayasan;
        }

        return $drawings;
    }
}