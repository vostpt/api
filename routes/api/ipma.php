<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use VOSTPT\Http\Controllers\IpmaWarningController;

/*
|--------------------------------------------------------------------------
| District endpoints: /v1/ipma/*
|--------------------------------------------------------------------------
|
*/

Route::prefix('v1/ipma')->name('ipma::')->group(static function (): void {
    Route::get('/warnings', [
        IpmaWarningController::class,
        'index',
    ])->name('warnings::index');
});
