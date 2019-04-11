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
        'as'   => 'authenticate',
        'uses' => AuthController::class.'@authenticate',
    ]);

    Route::get('/refresh', [
        'as'   => 'refresh',
        'uses' => AuthController::class.'@refresh',
    ])->middleware('jwt-auth');
});
