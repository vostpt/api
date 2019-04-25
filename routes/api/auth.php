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

Route::prefix('v1/auth')->name('auth::')->group(function () {
    Route::post('/', [
        AuthController::class,
        'authenticate',
    ])->name('authenticate');

    Route::get('/refresh', [
        AuthController::class,
        'refresh',
    ])->name('refresh')->middleware('jwt-auth');
});
