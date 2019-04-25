<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use VOSTPT\Http\Controllers\CountyController;

/*
|--------------------------------------------------------------------------
| County endpoints: /v1/counties/*
|--------------------------------------------------------------------------
|
*/

Route::prefix('v1/counties')->name('counties::')->group(function () {
    Route::get('/', [
        CountyController::class,
        'index',
    ])->name('index');

    Route::get('/{County}', [
        CountyController::class,
        'view',
    ])->name('view');
});
