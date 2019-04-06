<?php

declare(strict_types=1);

namespace VOSTPT\API\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * {@inheritDoc}
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }
}
