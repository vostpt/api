<?php

declare(strict_types=1);

namespace VOSTPT\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

class ValidateJsonApiRequest
{
    private const MEDIA_TYPE = 'application/vnd.api+json';

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $contentTypeHeader = $request->header('Content-Type');

        if ($contentTypeHeader && $contentTypeHeader !== self::MEDIA_TYPE && \mb_stripos($contentTypeHeader, self::MEDIA_TYPE) !== false) {
            throw new UnsupportedMediaTypeHttpException('Unsupported media type');
        }

        $acceptHeader = $request->header('Accept');

        if ($acceptHeader && $acceptHeader !== self::MEDIA_TYPE && \mb_stripos($acceptHeader, self::MEDIA_TYPE) !== false) {
            throw new NotAcceptableHttpException('Not acceptable');
        }

        return $next($request);
    }
}
