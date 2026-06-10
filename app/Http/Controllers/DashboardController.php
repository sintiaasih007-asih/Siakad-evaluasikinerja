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
use App\Models\AbsensiGuru;
use App\Models\UserLogin;
use Carbon\Carbon;
use Illuminate\Support\Str;

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

        // QR Token & statistik absensi guru hari ini
        $qrToken          = cache('qr_absensi_harian');
        $guruHadirHariIni = AbsensiGuru::whereDate('tanggal', today())->count();

        // Profil sekolah (untuk tampilkan kurikulum di dashboard)
        $profilSekolah = \App\Models\ProfileSekolah::first();

        return view('dashboard.admin', [
            'totalSiswa'       => Siswa::count(),
            'totalGuru'        => Guru::count(),
            'totalKelas'       => Kelas::count(),
            'totalMapel'       => Mapel::count(),
            'pengumuman'       => Pengumuman::latest()->take(5)->get(),
            'agenda'           => Agenda::latest()->take(5)->get(),
            'loginPerHari'     => $loginPerHari,
            'qrToken'          => $qrToken,
            'guruHadirHariIni' => $guruHadirHariIni,
            'profilSekolah'    => $profilSekolah,
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
        $guru = $user->guru;

        // Hari ini dalam bahasa Indonesia (sesuai kolom hari di jadwals)
        $hariIni     = now()->locale('id')->isoFormat('dddd'); // Senin, Selasa, dst.
        $hariInggris = now()->format('l');                     // Monday, Tuesday, dst.

        /*
        |----------------------------------------------------------------------
        | JADWAL HARI INI — hanya milik guru yang login
        |----------------------------------------------------------------------
        */
        $jadwals = collect();
        if ($guru) {
            $jadwals = Jadwal::with(['mapel', 'kelas'])
                ->where('guru_id', $guru->id)
                ->where(function ($q) use ($hariIni, $hariInggris) {
                    $q->whereRaw('LOWER(hari) = ?', [strtolower($hariIni)])
                      ->orWhereRaw('LOWER(hari) = ?', [strtolower($hariInggris)]);
                })
                ->orderBy('jam_masuk')
                ->get();
        }

        /*
        |----------------------------------------------------------------------
        | ID kelas yang diajar guru ini
        |----------------------------------------------------------------------
        */
        $kelasIds = $guru
            ? Jadwal::where('guru_id', $guru->id)->pluck('kelas_id')->unique()
            : collect();

        /*
        |----------------------------------------------------------------------
        | TOTAL SISWA — hanya siswa di kelas yang diampu
        |----------------------------------------------------------------------
        */
        $totalSiswa = $kelasIds->isNotEmpty()
            ? Siswa::whereIn('kelas_id', $kelasIds)->count()
            : 0;

        /*
        |----------------------------------------------------------------------
        | TOTAL ABSENSI — hanya pertemuan yang dibuat guru ini
        |----------------------------------------------------------------------
        */
        $totalAbsensi = $guru
            ? Absensi::where('guru_id', $guru->id)->count()
            : 0;

        /*
        |----------------------------------------------------------------------
        | TOTAL NILAI — hanya nilai yang diinput guru ini
        |----------------------------------------------------------------------
        */
        $totalNilai = $guru
            ? Nilai::where('guru_id', $guru->id)->count()
            : 0;

        $totalPengumuman = Pengumuman::count();
        $pengumuman      = Pengumuman::latest()->take(5)->get();
        $agenda          = Agenda::latest()->take(5)->get();

        return view('dashboard.guruu', compact(
            'totalSiswa',
            'totalAbsensi',
            'totalNilai',
            'totalPengumuman',
            'jadwals',
            'pengumuman',
            'agenda',
            'hariIni'
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
        $guru = $user->guru;

        // Hari ini
        $hariIni     = now()->locale('id')->isoFormat('dddd');
        $hariInggris = now()->format('l');

        /*
        |----------------------------------------------------------------------
        | JADWAL HARI INI — hanya milik guru/wakel yang login
        |----------------------------------------------------------------------
        */
        $jadwals = collect();
        if ($guru) {
            $jadwals = Jadwal::with(['mapel', 'kelas'])
                ->where('guru_id', $guru->id)
                ->where(function ($q) use ($hariIni, $hariInggris) {
                    $q->whereRaw('LOWER(hari) = ?', [strtolower($hariIni)])
                      ->orWhereRaw('LOWER(hari) = ?', [strtolower($hariInggris)]);
                })
                ->orderBy('jam_masuk')
                ->get();
        }

        /*
        |----------------------------------------------------------------------
        | Kelas binaan wali kelas (relasi guru_id di tabel kelas)
        |----------------------------------------------------------------------
        */
        $kelasWali = $guru
            ? Kelas::where('guru_id', $guru->id)->first()
            : null;

        /*
        |----------------------------------------------------------------------
        | Semua kelas yang diajar (dari jadwal)
        |----------------------------------------------------------------------
        */
        $kelasIds = $guru
            ? Jadwal::where('guru_id', $guru->id)->pluck('kelas_id')->unique()
            : collect();

        // Sertakan kelas binaan jika belum ada
        if ($kelasWali && !$kelasIds->contains($kelasWali->id)) {
            $kelasIds->push($kelasWali->id);
        }

        /*
        |----------------------------------------------------------------------
        | TOTAL SISWA — siswa di kelas yang diampu + kelas binaan
        |----------------------------------------------------------------------
        */
        $totalSiswa = $kelasIds->isNotEmpty()
            ? Siswa::whereIn('kelas_id', $kelasIds)->count()
            : 0;

        /*
        |----------------------------------------------------------------------
        | TOTAL ABSENSI — pertemuan absensi yang dibuat guru ini
        |----------------------------------------------------------------------
        */
        $totalAbsensi = $guru
            ? Absensi::where('guru_id', $guru->id)->count()
            : 0;

        /*
        |----------------------------------------------------------------------
        | TOTAL NILAI — nilai yang diinput guru ini
        |----------------------------------------------------------------------
        */
        $totalNilai = $guru
            ? Nilai::where('guru_id', $guru->id)->count()
            : 0;

        $totalPengumuman = Pengumuman::count();
        $pengumuman      = Pengumuman::latest()->take(5)->get();
        $agenda          = Agenda::latest()->take(5)->get();

        return view('dashboard.wakel', compact(
            'totalSiswa',
            'totalAbsensi',
            'totalNilai',
            'totalPengumuman',
            'kelasWali',
            'jadwals',
            'pengumuman',
            'agenda',
            'hariIni'
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