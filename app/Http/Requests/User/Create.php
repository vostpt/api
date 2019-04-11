<?php

declare(strict_types=1);

namespace VOSTPT\Http\Requests\User;

use Illuminate\Validation\Rule;
use VOSTPT\Http\Requests\Request;

class Create extends Request
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'surname' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
            'password' => [
                'required',
                'confirmed',
            ],
            'password_confirmation' => [
                'required',
            ],
        ];
    }
}
