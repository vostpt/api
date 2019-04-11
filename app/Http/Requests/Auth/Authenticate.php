<?php

declare(strict_types=1);

namespace VOSTPT\Http\Requests\Auth;

use VOSTPT\Http\Requests\Request;

class Authenticate extends Request
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
            ],
        ];
    }
}
