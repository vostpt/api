<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use VOSTPT\Http\Controllers\EventController;

/*
|--------------------------------------------------------------------------
| Event endpoints: /v1/events/*
|--------------------------------------------------------------------------
|
*/

Route::prefix('v1/events')->name('events::')->group(function () {
    Route::get('/', [
        EventController::class,
        'index',
    ])->name('index');

    Route::post('/', [
        EventController::class,
        'create',
    ])->name('create')->middleware('jwt-auth');

    Route::get('/{Event}', [
        EventController::class,
        'view',
    ])->name('view');

    Route::patch('/{Event}', [
        EventController::class,
        'update',
    ])->name('update')->middleware('jwt-auth');

    Route::delete('/{Event}', [
        EventController::class,
        'delete',
    ])->name('delete')->middleware('jwt-auth');
});
