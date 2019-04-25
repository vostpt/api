<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use VOSTPT\Http\Controllers\AcronymController;

/*
|--------------------------------------------------------------------------
| Acronym endpoints: /v1/acronyms/*
|--------------------------------------------------------------------------
|
*/

Route::prefix('v1/acronyms')->name('acronyms::')->group(function () {
    Route::get('/', [
        AcronymController::class,
        'index',
    ])->name('index');

    Route::post('/', [
        AcronymController::class,
        'create',
    ])->name('create')->middleware('jwt-auth');

    Route::get('/{Acronym}', [
        AcronymController::class,
        'view',
    ])->name('view');

    Route::patch('/{Acronym}', [
        AcronymController::class,
        'update',
    ])->name('update')->middleware('jwt-auth');

    Route::delete('/{Acronym}', [
        AcronymController::class,
        'delete',
    ])->name('delete')->middleware('jwt-auth');
});
