<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AbsensiGuru;
use App\Models\Guru;
use App\Models\ProfileSekolah;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanAbsensiGuruExport;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanAbsensiGuruController extends Controller
{
    public function index(Request $request)
    {
        $guru = Guru::orderBy('nama')->get();

        $query = AbsensiGuru::with('guru');

        if ($request->filled('guru')) {
            $query->where('guru_id', $request->guru);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_awal);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }

        $absensi = $query->latest()->paginate(15);

        // ==========================
        // REKAP SESUAI FILTER
        // ==========================
        $hadir = (clone $query)
            ->where('status', 'Hadir')
            ->count();

        $terlambat = (clone $query)
            ->where('status', 'Terlambat')
            ->count();

        $izin = (clone $query)
            ->whereIn('status', ['Izin', 'Sakit'])
            ->count();

        $alpa = (clone $query)
            ->where('status', 'Alpa')
            ->count();

        return view(
            'admin.laporan-absensi-guru.index',
            compact(
                'guru',
                'absensi',
                'hadir',
                'terlambat',
                'izin',
                'alpa'
            )
        );
    }

    public function exportPdf(Request $request)
    {
        $query = AbsensiGuru::with('guru');

        if ($request->filled('guru')) {
            $query->where('guru_id', $request->guru);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_awal);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }

        $absensi = $query->latest()->get();

        $profil = ProfileSekolah::first();

        // Rekap sesuai filter
        $hadir = (clone $query)
            ->where('status', 'Hadir')
            ->count();

        $terlambat = (clone $query)
            ->where('status', 'Terlambat')
            ->count();

        $izin = (clone $query)
            ->where('status', 'Izin')
            ->count();

        $sakit = (clone $query)
            ->where('status', 'Sakit')
            ->count();

        $alpa = (clone $query)
            ->where('status', 'Alpa')
            ->count();

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
            'admin.laporan-absensi-guru.pdf',
            compact(
                'absensi',
                'profil',
                'hadir',
                'terlambat',
                'izin',
                'sakit',
                'alpa',
                'periode'
            )
        );

        return $pdf->stream('laporan-absensi-guru.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new LaporanAbsensiGuruExport($request),
            'laporan_absensi_guru.xlsx'
        );
    }
}