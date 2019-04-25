<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use VOSTPT\Http\Controllers\DistrictController;

/*
|--------------------------------------------------------------------------
| District endpoints: /v1/districts/*
|--------------------------------------------------------------------------
|
*/

Route::prefix('v1/districts')->name('districts::')->group(function () {
    Route::get('/', [
        DistrictController::class,
        'index',
    ])->name('index');

    Route::get('/{District}', [
        DistrictController::class,
        'view',
    ])->name('view');
});
