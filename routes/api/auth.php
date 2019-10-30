<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use VOSTPT\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Authentication endpoints: /v1/auth/*
|--------------------------------------------------------------------------
|
*/

Route::prefix('v1/auth')->name('auth::')->group(static function (): void {
    Route::post('/', [
        AuthController::class,
        'authenticate',
    ])->name('authenticate');

    Route::get('/verify', [
        AuthController::class,
        'verify',
    ])->name('verify')->middleware('jwt-auth');

    Route::get('/refresh', [
        AuthController::class,
        'refresh',
    ])->name('refresh')->middleware('jwt-auth');
});
