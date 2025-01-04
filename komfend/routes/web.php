<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\Admin\AbsensiController as AdminAbsensiController;
use App\Http\Controllers\Admin\PdfController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('Absensi');
});

Route::prefix('/admin')->namespace('App\Http\Controllers\Admin')->group(function () {
    Route::match(['get', 'post'], 'login', 'AdminController@Login')->name('Admin.Login');

    Route::middleware(AdminMiddleware::class)->group(function () {
        Route::get('logout', 'AdminController@Logout')->name('Admin.Logout');
        Route::get('dashboard', 'AdminController@Dashboard')->name('Admin.Dashboard');
        Route::resource('users', UserController::class);
        Route::get('scan/{id}', [UserController::class, 'scan']);
        Route::get('/download-kartu{karyawan}', [PdfController::class, 'downloadKartuSatuan'])->name('download.kartu');
        Route::put('/admin/karyawan//{id}', [UserController::class, 'update'])->name('update.karyawan');
        Route::resource('absensis', AdminAbsensiController::class);
        Route::patch('/absensis/{id}/publish', [AdminAbsensiController::class, 'toggleStatus'])->name('toggle.status');
        Route::get('/dataabsen', 'AdminController@DataAbsen')->name('data.absen');
    });

});

// Route::middleware(AbsensiMiddleware::class)->group(function () {
//     Route::get('/absen', [AbsensiController::class, 'Index'])->name('Absensi');
//     Route::post('absen/create', [AbsensiController::class, 'Store'])->name('absen.store');
// });

Route::get('/absen', [AbsensiController::class, 'Index'])->name('Absensi');
Route::post('absen/create', [AbsensiController::class, 'Store'])->name('absen.store');
Route::get('/notfound', function () {
    return view('Absensi.PageNotFound');
})->name('notfound');
