<?php

declare(strict_types=1);

namespace VOSTPT\API\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

class JsonApiRequest
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @throws \Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('Content-Type') !== 'application/vnd.api+json') {
            throw new UnsupportedMediaTypeHttpException('Wrong media type');
        }

        return $next($request);
    }
}
