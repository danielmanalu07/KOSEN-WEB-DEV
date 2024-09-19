<?php

use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/user/login');
});

Route::prefix('/admin')->namespace('App\Http\Controllers\Admin')->group(function () {
    Route::middleware(AuthMiddleware::class)->group(function () {
        Route::get('dashboard', 'AdminController@Dashboard')->name('dashboard.admin');
        Route::resource('users', 'UserController');
    });
});

Route::prefix('/user')->namespace('App\Http\Controllers')->group(function () {
    Route::match(['get', 'post'], 'login', 'Auth\AuthController@Login')->name('login');
    Route::get('logout', 'Auth\AuthController@Logout')->name('logout')->middleware(AuthMiddleware::class);
});
