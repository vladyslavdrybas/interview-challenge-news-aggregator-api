<?php

use App\Http\Controllers\Api\ApiHomeController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v'. config('app.api.version') . '/auth')
    ->middleware('guest')
    ->group(function () {
        Route::post('/register', [RegisterController::class, 'store'])->name('api.auth.register');
        Route::post('/login', [LoginController::class, 'store'])->name('api.auth.login');
    });

Route::prefix('/v'. config('app.api.version'))
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/', [ApiHomeController::class, 'index'])->name('api.home');
        Route::get('/auth/logout', [LogoutController::class, 'destroy'])->name('api.auth.logout');
    });
