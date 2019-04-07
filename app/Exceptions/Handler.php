<?php

declare(strict_types=1);

namespace VOSTPT\API\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * {@inheritDoc}
     */
    public function render($request, Exception $exception)
    {
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
