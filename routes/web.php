<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\SetupPasswordController;
use App\Http\Controllers\SikapController;
use App\Http\Controllers\KedisiplinanController;
use App\Http\Controllers\WaliKelasController;
use App\Http\Controllers\RekapNilaiKelasController;
use App\Http\Controllers\RekapEvaluasiController;
use App\Http\Controllers\RekapEvaluasiKelasController;
use App\Http\Controllers\EvaluasiBulananController;
use App\Http\Controllers\EvaluasiSemesteranController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\OrangTua\NilaiAnakController;
use App\Http\Controllers\Orangtua\AbsensiAnakController;
use App\Http\Controllers\AbsensiGuruController;
use App\Http\Controllers\Orangtua\PenilaianKarakterController;
use App\Http\Controllers\Admin\LaporanAbsensiGuruController;
use App\Http\Controllers\Admin\ProfileSekolahController;
use App\Http\Controllers\Admin\QrAbsensiGuruController;
use App\Http\Controllers\Admin\LaporanAbsensiSiswaController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/admin', [DashboardController::class, 'admin']);
    Route::get('/guru', [DashboardController::class, 'guru']);
    Route::get('/kepsek', [DashboardController::class, 'kepsek']);
    Route::get('/wakel', [DashboardController::class, 'wakel']);
    Route::get('/ortu', [DashboardController::class, 'ortu']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('siswa', SiswaController::class);
    Route::resource('guru', GuruController::class);
    Route::resource('tahun-ajaran', TahunAjaranController::class);
    Route::resource('kelas', KelasController::class)->middleware('auth');
    Route::resource('mapel', MapelController::class);
    Route::resource('jadwal', JadwalController::class);
    Route::get('/absensi', [AbsensiController::class,'index'])->name('absensi.index');
    Route::get('/absensi/create/{id}', [AbsensiController::class,'create'])->name('absensi.create');
    Route::post('/absensi/store', [AbsensiController::class,'store'])->name('absensi.store');
    Route::post('/absensi/update-detail', [AbsensiController::class, 'updateDetail'])->name('absensi.update-detail');
    Route::get('/nilai', [NilaiController::class,'index'])->name('nilai.index');
    Route::get('/nilai/create/{id}', [NilaiController::class,'create'])->name('nilai.create');
    Route::post('/nilai/store', [NilaiController::class,'store'])->name('nilai.store');
    Route::resource('pengumuman', PengumumanController::class)->except(['index','create','edit','show']);
    Route::post('/agenda', [AgendaController::class, 'store'])->name('agenda.store');
    Route::put('/agenda/{id}', [AgendaController::class, 'update']);
    Route::delete('/agenda/{id}', [AgendaController::class, 'destroy']);

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['auth'])
        ->name('dashboard');
});

Route::middleware(['auth','admin'])->group(function () {
    Route::get('/users/{id}/resend', [UserController::class,'resend'])->name('users.resend');
    Route::get('/users/{id}/toggle', [UserController::class,'toggle'])->name('users.toggle');
    Route::resource('users', UserController::class);
    
    Route::get(
        '/laporan-absensi-guru',
        [LaporanAbsensiGuruController::class, 'index']
    )->name('laporan-absensi-guru.index');

    Route::get(
        '/laporan-absensi-guru/pdf',
        [LaporanAbsensiGuruController::class, 'exportPdf']
    )->name('laporan-absensi-guru.pdf');

    Route::get(
        '/laporan-absensi-guru/excel',
        [LaporanAbsensiGuruController::class, 'exportExcel']
    )->name('laporan-absensi-guru.excel');

    Route::get(
        '/laporan-absensi-siswa',
        [LaporanAbsensiSiswaController::class,'index']
    )->name('laporan-absensi-siswa.index');

    Route::get(
        '/laporan-absensi-siswa/pdf',
        [LaporanAbsensiSiswaController::class,'exportPdf']
    )->name('laporan-absensi-siswa.pdf');

    Route::get(
        '/laporan-absensi-siswa/excel',
        [LaporanAbsensiSiswaController::class,'exportExcel']
    )->name('laporan-absensi-siswa.excel');


    Route::get(
        '/profil-sekolah',
        [ProfileSekolahController::class, 'index']
    )->name('profil-sekolah.index');

    Route::put(
        '/profil-sekolah',
        [ProfileSekolahController::class, 'update']
    )->name('profil-sekolah.update');

    // QR Absensi Guru — hanya admin
    Route::get('/qr-absensi-guru', [QrAbsensiGuruController::class, 'index'])
        ->name('qr-absensi-guru.index');

    Route::post('/qr-absensi-guru/generate', [QrAbsensiGuruController::class, 'generate'])
        ->name('qr-absensi-guru.generate');

    Route::post('/qr-absensi-guru/reset', [QrAbsensiGuruController::class, 'reset'])
        ->name('qr-absensi-guru.reset');

    // Toggle Evaluasi (admin)
    Route::post('/evaluasi/toggle', [\App\Http\Controllers\Admin\ProfileSekolahController::class, 'toggleEvaluasi'])
        ->name('evaluasi.toggle');
});

Route::get('/setup-password/{token}', [SetupPasswordController::class,'form']);
Route::post('/setup-password/{token}', [SetupPasswordController::class,'save']);

Route::get('/sikap', [SikapController::class,'index'])->name('sikap.index');
Route::get('/sikap/create/{id}', [SikapController::class,'create'])->name('sikap.create');
Route::post('/sikap/store', [SikapController::class,'store'])->name('sikap.store');
Route::get('/sikap/riwayat/{id}', [SikapController::class,'riwayat'])->name('sikap.riwayat');

Route::get('/kedisiplinan', [KedisiplinanController::class,'index'])->name('kedisiplinan.index');
Route::get('/kedisiplinan/create/{id}', [KedisiplinanController::class,'create'])->name('kedisiplinan.create');
Route::post('/kedisiplinan/store', [KedisiplinanController::class,'store'])->name('kedisiplinan.store');
Route::get('/kedisiplinan/riwayat/{id}', [KedisiplinanController::class,'riwayat'])->name('kedisiplinan.riwayat');

Route::resource('siswa', SiswaController::class);

Route::get('/riwayat', [SiswaController::class, 'riwayatKelas'])
    ->name('riwayat.kelas');
Route::post('/naik-kelas', [SiswaController::class, 'naikKelas'])
    ->name('siswa.naikKelas');
Route::get('/alumni', [SiswaController::class, 'alumni'])
    ->name('alumni.index');

Route::get('/data-wali-kelas', [WaliKelasController::class, 'dataKelasBinaan'])->middleware('auth');

Route::middleware(['auth'])->group(function () {

    Route::get('/rekap-nilai-kelas', [RekapNilaiKelasController::class, 'index'])
        ->name('rekap.nilai.kelas');

});

Route::middleware(['auth'])->group(function(){

    Route::get(
        '/absensi-guru',
        [AbsensiGuruController::class,'index']
    )->name('absensi-guru.index');

    Route::post(
        '/absensi-guru/store',
        [AbsensiGuruController::class, 'store']
    )->name('absensi-guru.store');

});


Route::prefix('orang-tua')
    ->middleware('auth')
    ->group(function () {

        Route::get('/nilai-anak', [NilaiAnakController::class, 'index'])
            ->name('orangtua.nilai');

});

Route::prefix('orang-tua')->middleware(['auth'])->group(function () {
    Route::get('/absensi-anak', [AbsensiAnakController::class, 'index'])
        ->name('orangtua.absensi');
});


Route::prefix('orang-tua')
    ->middleware('auth')
    ->group(function () {

        Route::get('/penilaian-karakter', [PenilaianKarakterController::class, 'index'])
            ->name('orangtua.karakter');

});


Route::middleware(['auth'])->group(function () {

    Route::get('/rekap-evaluasi-kelas', [RekapEvaluasiKelasController::class,'index'])
        ->name('rekap.evaluasi.kelas');

});

Route::middleware(['auth'])->group(function () {

    Route::get('/evaluasi-bulanan', 
        [EvaluasiBulananController::class, 'index']
    )->name('evaluasi.bulanan');

    Route::get('/hasil-evaluasi-semesteran',
        [EvaluasiSemesteranController::class, 'index']
    )->name('evaluasi.semesteran');

});


require __DIR__.'/auth.php';
