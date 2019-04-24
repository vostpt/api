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
        'as'   => 'index',
        'uses' => DistrictController::class.'@index',
    ]);

    Route::get('/{District}', [
        'as'   => 'view',
        'uses' => DistrictController::class.'@view',
    ]);
});
