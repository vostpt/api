<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use VOSTPT\Http\Controllers\OccurrenceController;

/*
|--------------------------------------------------------------------------
| Occurrence endpoints: /v1/occurrences/*
|--------------------------------------------------------------------------
|
*/

Route::prefix('v1/occurrences')->name('occurrences::')->group(function () {
    Route::get('/', [
        OccurrenceController::class,
        'index',
    ])->name('index');

    Route::get('/{Occurrence}', [
        OccurrenceController::class,
        'view',
    ])->name('view');

    Route::patch('/{Occurrence}', [
        OccurrenceController::class,
        'update',
    ])->name('update')->middleware('jwt-auth');
});
