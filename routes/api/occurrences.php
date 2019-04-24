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
        'as'   => 'index',
        'uses' => OccurrenceController::class.'@index',
    ]);

    Route::get('/prociv/{ProCivOccurrence}', [
        'as'   => 'prociv::view',
        'uses' => ProCivOccurrenceController::class.'@view',
    ]);

    Route::get('/{Occurrence}', [
        'as'   => 'view',
        'uses' => OccurrenceController::class.'@view',
    ]);
});
