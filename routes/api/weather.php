<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use VOSTPT\Http\Controllers\WeatherObservationController;

/*
|--------------------------------------------------------------------------
| Weather endpoints: /v1/weather/*
|--------------------------------------------------------------------------
|
*/

Route::prefix('v1/weather')->name('weather::')->group(static function (): void {
    Route::prefix('observations')->name('observations::')->group(static function (): void {
        Route::get('/', [
            WeatherObservationController::class,
            'index',
        ])->name('index');

        Route::get('/{WeatherObservation}', [
            WeatherObservationController::class,
            'view',
        ])->name('view');
    });
});
