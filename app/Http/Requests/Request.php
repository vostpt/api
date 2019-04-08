<?php

declare(strict_types=1);

namespace VOSTPT\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Request extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    protected function failedAuthorization(): void
    {
        throw new AccessDeniedHttpException('Action Forbidden');
    }
}
