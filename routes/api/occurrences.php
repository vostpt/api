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
        'as'   => 'index',
        'uses' => OccurrenceController::class.'@index',
    ]);

    Route::get('/{occurrence}', [
        'as'   => 'view',
        'uses' => OccurrenceController::class.'@view',
    ]);
});
