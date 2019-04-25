<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use VOSTPT\Http\Controllers\FallbackController;

Route::fallback([FallbackController::class, 'documentation']);
