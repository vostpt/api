<?php

declare(strict_types=1);

namespace VOSTPT\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;

class Handler extends ExceptionHandler
{
    /**
     * {@inheritDoc}
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof JWTException) {
            $exception = new UnauthorizedHttpException('Newauth realm="VOST PT"', $exception->getMessage(), $exception);
        }

        if ($exception instanceof HttpException) {
            return response()->error($exception);
        }

        if ($exception instanceof ValidationException) {
            return response()->validation($exception);
        }

        return response()->json([
            'errors' => [
                [
                    'id'     => $exception->getCode(),
                    'detail' => $exception->getMessage(),
                ],
            ],
        ], 500);
    }
}
