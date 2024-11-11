<?php

use App\Http\Controllers\Api\ApiHomeController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v'. config('app.api.version') . '/auth')
    ->group(function () {
        Route::post('/register', [AuthController::class, 'register'])->name('api.auth.user.register');
    });

Route::prefix('/v'. config('app.api.version'))
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/', [ApiHomeController::class, 'index'])->name('api.home');
    });
