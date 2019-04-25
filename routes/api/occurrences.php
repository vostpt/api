<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use VOSTPT\Http\Controllers\OccurrenceController;
use VOSTPT\Http\Controllers\ProCivOccurrenceController;

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

    Route::get('/prociv/{ProCivOccurrence}', [
        ProCivOccurrenceController::class,
        'view',
    ])->name('prociv::view');

    Route::get('/{Occurrence}', [
        OccurrenceController::class,
        'view',
    ])->name('view');
});
