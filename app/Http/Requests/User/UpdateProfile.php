<?php

declare(strict_types=1);

namespace VOSTPT\Http\Requests\User;

use Illuminate\Validation\Rule;
use VOSTPT\Http\Requests\Request;
use VOSTPT\Models\User;

class UpdateProfile extends Request
{
    /**
     * {@inheritDoc}
     */
    public function authorize(): bool
    {
        return $this->user()->can('updateProfile', User::class);
    }

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'name' => [
                'string',
                'max:255',
            ],
            'surname' => [
                'string',
                'max:255',
            ],
            'email' => [
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($this->user(), 'email'),
            ],
            'password' => [
                'required_with:password_confirmation',
                'confirmed',
            ],
            'password_confirmation' => [
                'required_with:password',
            ],
        ];
    }
}
