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
        'as'   => 'index',
        'uses' => ParishController::class.'@index',
    ]);

    Route::get('/{parish}', [
        'as'   => 'view',
        'uses' => ParishController::class.'@view',
    ]);
});
