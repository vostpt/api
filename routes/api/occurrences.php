<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use VOSTPT\Http\Controllers\OccurrenceController;
use VOSTPT\Http\Controllers\OccurrenceFamilyController;
use VOSTPT\Http\Controllers\OccurrenceReportController;
use VOSTPT\Http\Controllers\OccurrenceSpeciesController;
use VOSTPT\Http\Controllers\OccurrenceStatusController;
use VOSTPT\Http\Controllers\OccurrenceTypeController;

/*
|--------------------------------------------------------------------------
| Occurrence endpoints: /v1/occurrences/*
|--------------------------------------------------------------------------
|
*/

Route::prefix('v1/occurrences')->name('occurrences::')->group(function () {
    Route::get('/reports', [
        OccurrenceReportController::class,
        'generate',
    ])->name('reports::generate');

    Route::get('/reports/{signature}', [
        OccurrenceReportController::class,
        'download',
    ])->name('reports::download');

    Route::get('/', [
        OccurrenceController::class,
        'index',
    ])->name('index');

    Route::get('/families', [
        OccurrenceFamilyController::class,
        'index',
    ])->name('families::index');

    Route::get('/species', [
        OccurrenceSpeciesController::class,
        'index',
    ])->name('species::index');

    Route::get('/types', [
        OccurrenceTypeController::class,
        'index',
    ])->name('types::index');

    Route::get('/statuses', [
        OccurrenceStatusController::class,
        'index',
    ])->name('statuses::index');

    Route::get('/{Occurrence}', [
        OccurrenceController::class,
        'view',
    ])->name('view');

    Route::patch('/{Occurrence}', [
        OccurrenceController::class,
        'update',
    ])->name('update')->middleware('jwt-auth');
});
