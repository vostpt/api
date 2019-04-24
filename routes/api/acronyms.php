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

    Route::post('/', [
        'as'   => 'create',
        'uses' => AcronymController::class.'@create',
    ])->middleware('jwt-auth');

    Route::get('/{Acronym}', [
        'as'   => 'view',
        'uses' => AcronymController::class.'@view',
    ]);

    Route::patch('/{Acronym}', [
        'as'   => 'update',
        'uses' => AcronymController::class.'@update',
    ])->middleware('jwt-auth');

    Route::delete('/{Acronym}', [
        'as'   => 'delete',
        'uses' => AcronymController::class.'@delete',
    ])->middleware('jwt-auth');
});
