<?php

namespace App\Exports;

use App\Models\AbsensiDetail;
use App\Models\Kelas;
use App\Models\ProfileSekolah;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class LaporanAbsensiSiswaExport implements FromView, WithDrawings
{
    protected $kelasId;
    protected $tanggalAwal;
    protected $tanggalAkhir;
    protected $status;

    public function __construct(
        $kelasId = null,
        $tanggalAwal = null,
        $tanggalAkhir = null,
        $status = null
    ) {
        $this->kelasId      = $kelasId;
        $this->tanggalAwal  = $tanggalAwal;
        $this->tanggalAkhir = $tanggalAkhir;
        $this->status = $status;
    }

    public function view(): View
    {
        $query = AbsensiDetail::with([
            'siswa.kelas',
            'absensi.guru',
            'absensi.jadwal.mapel'
        ]);

        if ($this->kelasId) {

            $query->whereHas('siswa', function ($q) {

                $q->where(
                    'kelas_id',
                    $this->kelasId
                );

            });
        }

        if ($this->tanggalAwal) {

            $query->whereHas('absensi', function ($q) {

                $q->whereDate(
                    'tanggal',
                    '>=',
                    $this->tanggalAwal
                );

            });
        }

        if ($this->tanggalAkhir) {

            $query->whereHas('absensi', function ($q) {

                $q->whereDate(
                    'tanggal',
                    '<=',
                    $this->tanggalAkhir
                );

            });
        }

        $periode = 'Semua Periode';

        if ($this->tanggalAwal && $this->tanggalAkhir) {

            $periode =
                \Carbon\Carbon::parse($this->tanggalAwal)->format('d-m-Y')
                .' s/d '.
                \Carbon\Carbon::parse($this->tanggalAkhir)->format('d-m-Y');

        } elseif ($this->tanggalAwal) {

            $periode =
                'Mulai '.
                \Carbon\Carbon::parse($this->tanggalAwal)->format('d-m-Y');

        } elseif ($this->tanggalAkhir) {

            $periode =
                'Sampai '.
                \Carbon\Carbon::parse($this->tanggalAkhir)->format('d-m-Y');
        }

        $absensi = $query->latest()->get();

        $kelasNama = 'Semua Kelas';

        if ($this->kelasId) {

            $kelas = Kelas::find($this->kelasId);

            $kelasNama = $kelas?->nama_kelas ?? 'Semua Kelas';
        }

        $periode = 'Semua Periode';

        if ($this->tanggalAwal && $this->tanggalAkhir) {

            $periode =
                date('d-m-Y', strtotime($this->tanggalAwal))
                .' s/d '.
                date('d-m-Y', strtotime($this->tanggalAkhir));

        } elseif ($this->tanggalAwal) {

            $periode =
                'Mulai '
                . date('d-m-Y', strtotime($this->tanggalAwal));

        } elseif ($this->tanggalAkhir) {

            $periode =
                'Sampai '
                . date('d-m-Y', strtotime($this->tanggalAkhir));
        }

        return view(
            'admin.laporan-absensi-siswa.excel',
            [
                'profil'    => ProfileSekolah::first(),
                'absensi'   => $absensi,
                'kelasNama' => $kelasNama,
                'periode'   => $periode,

                'hadir' =>
                    $absensi->where('status','hadir')->count(),

                'izin' =>
                    $absensi->where('status','izin')->count(),

                'sakit' =>
                    $absensi->where('status','sakit')->count(),

                'alpa' =>
                    $absensi->where('status','alpha')->count(),
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