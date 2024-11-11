<?php

use App\Http\Controllers\Api\ApiHomeController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v'. config('app.api.version'))->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/', [ApiHomeController::class, 'index'])->name('api.home');
    });
