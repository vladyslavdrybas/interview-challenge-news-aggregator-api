<?php

use App\Http\Controllers\Api\ApiHomeController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v'. config('app.api.version'))->group(function () {
    Route::get('/', [ApiHomeController::class, 'index'])->name('api.home');
});
