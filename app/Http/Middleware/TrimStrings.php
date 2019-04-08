<?php

declare(strict_types=1);

namespace VOSTPT\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

class TrimStrings extends Middleware
{
    /**
     * {@inheritDoc}
     */
    protected $except = [
        'password',
        'password_confirmation',
    ];
}
