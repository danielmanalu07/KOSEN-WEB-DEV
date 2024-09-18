<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PositionsController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.login');
Route::post('/logout', [LoginController::class, 'logout'])->name('login.logout');

Route::middleware(['auth'])->group(function () {
    Route::resource('/attendance', AttendanceController::class);

    Route::get('/absen', function () {
        return view('absen');
    });
    Route::get('/rekap', function () {
        return view('rekap');
    });
    Route::resource('/position', PositionsController::class);


    // Route::get('/absen', action: [KelasController::class, 'absen']);
    // Route::get('/absen/{namaKelas}', [RekapController::class, 'index']);
    // Route::post('/absenAdd', [RekapController::class, 'store']);

    // Route::resource('/rekap', KehadiranController::class);
    // Route::post('/rekap/filter', [KehadiranController::class, 'filter'])->name('rekap.filter');
    // Route::post('/download-rekap', [KehadiranController::class, 'downloadRekap'])->name('rekap.download');

    // Route::get('/siswa/search', [SiswaController::class, 'search'])->name('siswa.search');
    // Route::resource('/siswa', SiswaController::class);
    // Route::resource('/kelas', KelasController::class);
    // Route::resource('/guru', GuruController::class);
    // Route::resource('/jadwal', JadwalController::class);
    // Route::resource('/pelajaran', PelajaranController::class);
});
