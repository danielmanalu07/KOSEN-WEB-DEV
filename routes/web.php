<?php

use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\PresenceController;
use App\Http\Controllers\Admin\UserController;
use App\Models\Presence;

Route::get('/', function () {
    return redirect('/user/login');
});

Route::prefix('/admin')->namespace('App\Http\Controllers\Admin')->group(function () {
    Route::middleware(AuthMiddleware::class)->group(function () {
        Route::get('dashboard', [AdminController::class, 'Dashboard'])->name('dashboard.admin');
        Route::resource('users', UserController::class);
        Route::resource('attendances', AttendanceController::class);
        Route::resource('presences', PresenceController::class);
        Route::get('/presences/show-qrcode', [PresenceController::class, 'showQrcode'])->name('presences.showQrcode');
        Route::post('/presences/generate/{id}', [PresenceController::class, 'generateQRCode'])->name('presences.generate');
    });
});

Route::prefix('/user')->namespace('App\Http\Controllers')->group(function () {
    Route::match(['get', 'post'], 'login', [App\Http\Controllers\Auth\AuthController::class, 'Login'])->name('login');
    Route::get('logout', [App\Http\Controllers\Auth\AuthController::class, 'Logout'])->name('logout')->middleware(AuthMiddleware::class);
});
