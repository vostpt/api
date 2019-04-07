<?php

declare(strict_types=1);

namespace VOSTPT\API\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * {@inheritDoc}
     */
    protected $middleware = [
        Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        Middleware\TrustProxies::class,
    ];

    /**
     * {@inheritDoc}
     */
    protected $middlewareGroups = [
        'web' => [],

        'api' => [
            'throttle:60,1',
            'bindings',
            'json-api',
        ],
    ];

    /**
     * {@inheritDoc}
     */
    protected $routeMiddleware = [
        'json-api' => Middleware\JsonApiRequest::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    ];

    /**
     * {@inheritDoc}
     */
    protected $middlewarePriority = [
        Middleware\JsonApiRequest::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ];
}
