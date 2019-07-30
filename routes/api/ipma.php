<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use VOSTPT\Http\Controllers\WarningController;

/*
|--------------------------------------------------------------------------
| District endpoints: /v1/ipma/*
|--------------------------------------------------------------------------
|
*/

Route::prefix('v1/ipma')->name('ipma::')->group(function () {
    Route::get('/warnings', [
        WarningController::class,
        'index',
    ])->name('warnings::index');
});
