<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use VOSTPT\Http\Controllers\AcronymController;

/*
|--------------------------------------------------------------------------
| Acronym endpoints: /v1/acronyms/*
|--------------------------------------------------------------------------
|
*/

Route::prefix('v1/acronyms')->name('acronyms::')->group(function () {
    Route::get('/', [
        'as'   => 'index',
        'uses' => AcronymController::class.'@index',
    ]);

    Route::get('/{acronym}', [
        'as'   => 'view',
        'uses' => AcronymController::class.'@view',
    ]);
});
