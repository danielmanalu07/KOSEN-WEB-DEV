<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PositionsController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;

// Route::get('/', [LoginController::class, 'index'])->name('login');
// Route::post('/login', [LoginController::class, 'login'])->name('login.login');
// Route::post('/logout', [LoginController::class, 'logout'])->name('login.logout');

Route::get('/', function () {
    return redirect('/user/login');
});

Route::prefix('/admin')->namespace('App\Http\Controllers\Admin')->group(function () {
    Route::middleware(AuthMiddleware::class)->group(function () {
        Route::get('dashboard', 'AdminController@Dashboard')->name('dashboard.admin');
    });
});

Route::prefix('/user')->namespace('App\Http\Controllers')->group(function () {
    Route::match(['get', 'post'], 'login', 'Auth\AuthController@Login')->name('login');
    Route::get('logout', 'Auth\AuthController@Logout')->name('logout')->middleware(AuthMiddleware::class);
});

Route::middleware(['auth'])->group(function () {
    Route::resource('/attendance', AttendanceController::class);

    Route::get('/absen', function () {
        return view('attendance.absen');
    });
    Route::get('/rekap', function () {
        return view('rekap');
    });
    Route::resource('/position', PositionsController::class);

});
