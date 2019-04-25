<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use VOSTPT\Http\Controllers\ParishController;

/*
|--------------------------------------------------------------------------
| Parish endpoints: /v1/parishes/*
|--------------------------------------------------------------------------
|
*/

Route::prefix('v1/parishes')->name('parishes::')->group(function () {
    Route::get('/', [
        ParishController::class,
        'index',
    ])->name('index');

    Route::get('/{Parish}', [
        ParishController::class,
        'view',
    ])->name('view');
});
