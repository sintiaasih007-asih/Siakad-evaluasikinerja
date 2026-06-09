<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Pengumuman;
use App\Models\Agenda;
use App\Models\Jadwal;
use App\Models\Nilai;
use App\Models\Absensi;
use App\Models\UserLogin;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD ADMIN
    |--------------------------------------------------------------------------
    */

    public function admin()
    {
        $loginPerHari = UserLogin::selectRaw("
            DATE(login_at) as tanggal,

            SUM(CASE WHEN role='guru' THEN 1 ELSE 0 END) as guru,

            SUM(CASE WHEN role='guru&wali_kelas' THEN 1 ELSE 0 END) as walikelas,

            SUM(CASE WHEN role='kepala_sekolah' THEN 1 ELSE 0 END) as kepsek,

            SUM(CASE WHEN role='orang_tua' THEN 1 ELSE 0 END) as orangtua
        ")
        ->whereDate('login_at', '>=', Carbon::now()->subDays(7))
        ->groupBy('tanggal')
        ->orderBy('tanggal')
        ->get();

        return view('dashboard.admin', [
            'totalSiswa'   => Siswa::count(),
            'totalGuru'    => Guru::count(),
            'totalKelas'   => Kelas::count(),
            'totalMapel'   => Mapel::count(),
            'pengumuman'   => Pengumuman::latest()->take(5)->get(),
            'agenda'       => Agenda::latest()->take(5)->get(),
            'loginPerHari' => $loginPerHari,
        ]);

    }

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD GURU
    |--------------------------------------------------------------------------
    */

    public function guru()
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | DATA STATISTIK
        |--------------------------------------------------------------------------
        */

        $totalSiswa = Siswa::count();

        $totalAbsensi = Absensi::count();

        $totalNilai = Nilai::count();

        $totalPengumuman = Pengumuman::count();

    
        /*
        |--------------------------------------------------------------------------
        | JADWAL
        |--------------------------------------------------------------------------
        */

        $jadwals = Jadwal::with(['mapel', 'kelas'])
            ->latest()
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | PENGUMUMAN
        |--------------------------------------------------------------------------
        */

        $pengumuman = Pengumuman::latest()
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | AGENDA
        |--------------------------------------------------------------------------
        */

        $agenda = Agenda::latest()
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view('dashboard.guruu', compact(
            'totalSiswa',
            'totalAbsensi',
            'totalNilai',
            'totalPengumuman',
            'jadwals',
            'pengumuman',
            'agenda'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD KEPSEK
    |--------------------------------------------------------------------------
    */

    public function kepsek()
    {
        return view('dashboard.kepsek', [

            // Statistik
            'totalSiswa'       => Siswa::count(),
            'totalGuru'        => Guru::count(),
            'totalKelas'       => Kelas::count(),
            'totalMapel'       => Mapel::count(),
            'totalAbsensi'     => Absensi::count(),
            'totalNilai'       => Nilai::count(),
            'totalPengumuman'  => Pengumuman::count(),

            // Data terbaru
            'pengumuman'       => Pengumuman::latest()->take(5)->get(),
            'agenda'           => Agenda::latest()->take(5)->get(),

            // Jadwal terbaru
            'jadwals'          => Jadwal::with(['guru', 'kelas', 'mapel'])
                                        ->latest()
                                        ->take(5)
                                        ->get(),

            // Guru terbaru
            'gurus'            => Guru::latest()->take(5)->get(),

            // Siswa terbaru
            'siswas'           => Siswa::latest()->take(5)->get(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD WALI KELAS
    |--------------------------------------------------------------------------
    */

    public function wakel()
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | DATA STATISTIK
        |--------------------------------------------------------------------------
        */

        $totalSiswa = Siswa::count();

        $totalAbsensi = Absensi::count();

        $totalNilai = Nilai::count();

        $totalPengumuman = Pengumuman::count();

        /*
        |--------------------------------------------------------------------------
        | KELAS WALI
        |--------------------------------------------------------------------------
        */

        $kelasWali = Kelas::where('guru_id', $user->id)->first();

        /*
        |--------------------------------------------------------------------------
        | JADWAL
        |--------------------------------------------------------------------------
        */

        $jadwals = Jadwal::with(['mapel', 'kelas'])
            ->latest()
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | PENGUMUMAN
        |--------------------------------------------------------------------------
        */

        $pengumuman = Pengumuman::latest()
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | AGENDA
        |--------------------------------------------------------------------------
        */

        $agenda = Agenda::latest()
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view('dashboard.wakel', compact(
            'totalSiswa',
            'totalAbsensi',
            'totalNilai',
            'totalPengumuman',
            'kelasWali',
            'jadwals',
            'pengumuman',
            'agenda'
        ));
    }



/*
|--------------------------------------------------------------------------
| DASHBOARD ORANG TUA
|--------------------------------------------------------------------------
*/

public function ortu()
{
    $user = Auth::user();

    /*
    |--------------------------------------------------------------------------
    | AMBIL DATA SISWA
    |--------------------------------------------------------------------------
    */

    $siswa = Siswa::with('kelas')
        ->find($user->siswa_id);

    /*
    |--------------------------------------------------------------------------
    | JIKA DATA SISWA TIDAK ADA
    |--------------------------------------------------------------------------
    */

    if (!$siswa) {

        return view('dashboard.ortu', [
            'siswa'             => null,
            'nilais'            => collect(),
            'absensis'          => collect(),
            'rataNilai'         => 0,
            'persentaseHadir'   => 0,
            'hadir'             => 0,
            'izin'              => 0,
            'sakit'             => 0,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DATA NILAI
    |--------------------------------------------------------------------------
    */

    $nilais = Nilai::where('siswa_id', $siswa->id)
        ->latest()
        ->get();

    /*
    |--------------------------------------------------------------------------
    | DATA ABSENSI
    |--------------------------------------------------------------------------
    */

    $absensis = \DB::table('absensi_details')
        ->join('absensis', 'absensi_details.absensi_id', '=', 'absensis.id')
        ->where('absensi_details.siswa_id', $siswa->id)
        ->select(
            'absensi_details.status',
            'absensis.tanggal',
            'absensis.pertemuan',
            'absensis.semester',
            'absensis.tahun_ajaran'
        )
        ->latest('absensis.tanggal')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | HITUNG NILAI
    |--------------------------------------------------------------------------
    */

    $rataNilai = $nilais->avg('nilai') ?? 0;

    /*
    |--------------------------------------------------------------------------
    | HITUNG ABSENSI
    |--------------------------------------------------------------------------
    */

    $hadir = $absensis->where('status', 'hadir')->count();

    $izin = $absensis->where('status', 'izin')->count();

    $sakit = $absensis->where('status', 'sakit')->count();

    $alpha = $absensis->where('status', 'alpha')->count();

    $totalAbsensi = $absensis->count();

    $persentaseHadir = $totalAbsensi > 0
        ? round(($hadir / $totalAbsensi) * 100)
        : 0;

    /*
    |--------------------------------------------------------------------------
    | RETURN VIEW
    |--------------------------------------------------------------------------
    */

    return view('dashboard.ortu', compact(
        'siswa',
        'nilais',
        'absensis',
        'rataNilai',
        'persentaseHadir',
        'hadir',
        'izin',
        'sakit',
        'alpha'
    ));
}

    /*
    |--------------------------------------------------------------------------
    | AUTO REDIRECT ROLE
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $role = Auth::user()->role;

        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if ($role == 'admin') {
            return $this->admin();
        }

        /*
        |--------------------------------------------------------------------------
        | GURU
        |--------------------------------------------------------------------------
        */

        if ($role == 'guru') {
            return $this->guru();
        }

        /*
        |--------------------------------------------------------------------------
        | GURU & WALI KELAS
        |--------------------------------------------------------------------------
        */

        if ($role == 'guru&wali_kelas') {
            return $this->wakel();
        }

        /*
        |--------------------------------------------------------------------------
        | KEPALA SEKOLAH
        |--------------------------------------------------------------------------
        */

        if ($role == 'kepala_sekolah') {
            return $this->kepsek();
        }

        /*
        |--------------------------------------------------------------------------
        | ORANG TUA
        |--------------------------------------------------------------------------
        */

        if ($role == 'orang_tua') {
            return $this->ortu();
        }

        abort(403);
    }
    
}