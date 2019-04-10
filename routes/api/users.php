<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use VOSTPT\Http\Controllers\UserController;
use VOSTPT\Http\Controllers\UserRoleController;

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
    ])->middleware('jwt-auth');

    Route::post('/', [
        'as'   => 'create',
        'uses' => UserController::class.'@create',
    ]);

    Route::get('/roles', [
        'as'   => 'roles::index',
        'uses' => UserRoleController::class.'@index',
    ])->middleware('jwt-auth');

    Route::get('/{user}', [
        'as'   => 'view',
        'uses' => UserController::class.'@view',
    ])->middleware('jwt-auth');

    Route::patch('/{user}', [
        'as'   => 'update',
        'uses' => UserController::class.'@update',
    ])->middleware('jwt-auth');
});
