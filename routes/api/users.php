<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use VOSTPT\Http\Controllers\UserController;
use VOSTPT\Http\Controllers\UserProfileController;
use VOSTPT\Http\Controllers\UserRoleController;

/*
|--------------------------------------------------------------------------
| User endpoints: /v1/users/*
|--------------------------------------------------------------------------
|
*/

Route::prefix('v1/users')->name('users::')->group(static function (): void {
    Route::get('/', [
        UserController::class,
        'index',
    ])->name('index')->middleware('jwt-auth');

    Route::post('/', [
        UserController::class,
        'create',
    ])->name('create')->middleware('throttle:8,10');

    Route::get('/profile', [
        UserProfileController::class,
        'view',
    ])->name('profile::view')->middleware('jwt-auth');

    Route::patch('/profile', [
        UserProfileController::class,
        'update',
    ])->name('profile::update')->middleware('jwt-auth');

    Route::get('/roles', [
        UserRoleController::class,
        'index',
    ])->name('roles::index')->middleware('jwt-auth');

    Route::get('/{User}', [
        UserController::class,
        'view',
    ])->name('view')->middleware('jwt-auth');

    Route::patch('/{User}', [
        UserController::class,
        'update',
    ])->name('update')->middleware('jwt-auth');
});
