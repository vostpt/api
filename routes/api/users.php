<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use VOSTPT\Http\Controllers\User\UserController;

/*
|--------------------------------------------------------------------------
| User endpoints: /v1/users/*
|--------------------------------------------------------------------------
|
*/

Route::prefix('v1/users')->name('users::')->group(function () {
    Route::get('/', [
        'as'   => 'index',
        'uses' => UserController::class.'@index',
    ]);

    Route::get('/{user}', [
        'as'   => 'view',
        'uses' => UserController::class.'@view',
    ]);
});
