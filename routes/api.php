<?php

use App\Http\Controllers\Api\ApiHomeController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\Auth\CredentialsController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\NewsAuthorController;
use App\Http\Controllers\Api\NewsCategoryController;
use App\Http\Controllers\Api\NewsSourceController;
use App\Http\Controllers\Api\UserFeedController;
use App\Http\Controllers\Api\UserPreferenceController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v'. config('app.api.version') . '/auth')
    ->middleware('guest')
    ->group(function () {
        Route::post('/register', [RegisterController::class, 'store'])->name('api.auth.register');
        Route::post('/login', [LoginController::class, 'store'])->name('api.auth.login');
        Route::post('/password-forgot', [CredentialsController::class, 'passwordForgot'])->name('password.email');
    });

Route::prefix('/v'. config('app.api.version') . '/auth')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/logout', [LogoutController::class, 'destroy'])->name('api.auth.logout');
        Route::post('/password-update', [CredentialsController::class, 'passwordUpdate'])->name('api.auth.password-update');
    });

Route::prefix('/v'. config('app.api.version'))
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/', [ApiHomeController::class, 'index'])->name('api.home');

        Route::get('/news-sources', [NewsSourceController::class, 'index']);
        Route::get('/news-categories', [NewsCategoryController::class, 'index']);
        Route::get('/news-authors', [NewsAuthorController::class, 'index']);
        Route::get('/user/feed', [UserFeedController::class, 'index'])->name('api.user.feed');
    });

Route::prefix('/v'. config('app.api.version') . '/articles')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/', [ArticleController::class, 'index'])->name('api.articles');
        Route::get('/{id}', [ArticleController::class, 'show'])->name('api.articles.show');
    });

Route::prefix('/v'. config('app.api.version') . '/user/preferences')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/', [UserPreferenceController::class, 'index'])->name('api.user.preferences.list');
        Route::post('/', [UserPreferenceController::class, 'store'])->name('api.user.preferences.store');
        Route::get('{id}', [UserPreferenceController::class, 'show'])->name('api.user.preferences.show');
        Route::put('{id}', [UserPreferenceController::class, 'update'])->name('api.user.preferences.update');
        Route::delete('{id}', [UserPreferenceController::class, 'destroy'])->name('api.user.preferences.destroy');
    });
