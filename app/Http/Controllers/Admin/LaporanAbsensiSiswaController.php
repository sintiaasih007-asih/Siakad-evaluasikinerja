<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbsensiDetail;
use App\Models\Kelas;
use App\Models\ProfileSekolah;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\LaporanAbsensiSiswaExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class LaporanAbsensiSiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = AbsensiDetail::with([
            'siswa.kelas',
            'absensi.guru',
            'absensi.jadwal'
        ]);

        // Filter kelas
        if ($request->filled('kelas_id')) {

            $query->whereHas('siswa', function ($q) use ($request) {

                $q->where('kelas_id', $request->kelas_id);

            });
        }

        // Filter tanggal awal
        if ($request->filled('tanggal_awal')) {

            $query->whereHas('absensi', function ($q) use ($request) {

                $q->whereDate(
                    'tanggal',
                    '>=',
                    $request->tanggal_awal
                );

            });
        }

        // Filter tanggal akhir
        if ($request->filled('tanggal_akhir')) {

            $query->whereHas('absensi', function ($q) use ($request) {

                $q->whereDate(
                    'tanggal',
                    '<=',
                    $request->tanggal_akhir
                );

            });
        }

        $absensi = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $kelas = Kelas::orderBy('nama_kelas')->get();

        // REKAP SESUAI FILTER
        $hadir = (clone $query)
            ->where('status', 'hadir')
            ->count();

        $izin = (clone $query)
            ->where('status', 'izin')
            ->count();

        $sakit = (clone $query)
            ->where('status', 'sakit')
            ->count();

        $alpa = (clone $query)
            ->where('status', 'alpha')
            ->count();

        return view(
            'admin.laporan-absensi-siswa.index',
            compact(
                'absensi',
                'kelas',
                'hadir',
                'izin',
                'sakit',
                'alpa'
            )
        );
    }

    public function exportPdf(Request $request)
    {
        $query = AbsensiDetail::with([
            'siswa.kelas',
            'absensi.guru'
        ]);

        // Filter kelas
        if ($request->filled('kelas_id')) {

            $query->whereHas('siswa', function ($q) use ($request) {

                $q->where('kelas_id', $request->kelas_id);

            });
        }

        // Filter tanggal awal
        if ($request->filled('tanggal_awal')) {

            $query->whereHas('absensi', function ($q) use ($request) {

                $q->whereDate(
                    'tanggal',
                    '>=',
                    $request->tanggal_awal
                );

            });
        }

        // Filter tanggal akhir
        if ($request->filled('tanggal_akhir')) {

            $query->whereHas('absensi', function ($q) use ($request) {

                $q->whereDate(
                    'tanggal',
                    '<=',
                    $request->tanggal_akhir
                );

            });
        }

        $absensi = $query
            ->latest()
            ->get();

        $profil = ProfileSekolah::first();

        $periode = 'Semua Periode';

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {

            $periode =
                \Carbon\Carbon::parse($request->tanggal_awal)->format('d-m-Y')
                .' s/d '.
                \Carbon\Carbon::parse($request->tanggal_akhir)->format('d-m-Y');

        } elseif ($request->filled('tanggal_awal')) {

            $periode =
                'Mulai '
                . \Carbon\Carbon::parse($request->tanggal_awal)->format('d-m-Y');

        } elseif ($request->filled('tanggal_akhir')) {

            $periode =
                'Sampai '
                . \Carbon\Carbon::parse($request->tanggal_akhir)->format('d-m-Y');
        }

        $pdf = Pdf::loadView(
            'admin.laporan-absensi-siswa.pdf',
            [
                'absensi' => $absensi,
                'profil' => $profil,
                'periode'=> $periode,

                'hadir' => (clone $query)
                    ->where('status', 'hadir')
                    ->count(),

                'izin' => (clone $query)
                    ->where('status', 'izin')
                    ->count(),

                'sakit' => (clone $query)
                    ->where('status', 'sakit')
                    ->count(),

                'alpa' => (clone $query)
                    ->where('status', 'alpha')
                    ->count(),
            ]
        );

        return $pdf->stream(
            'laporan-absensi-siswa.pdf'
        );
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new LaporanAbsensiSiswaExport(
                $request->kelas_id,
                $request->tanggal_awal,
                $request->tanggal_akhir,
                $request->status
            ),
            'laporan-absensi-siswa.xlsx'
        );
    }
}