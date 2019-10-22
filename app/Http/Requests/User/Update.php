<?php

declare(strict_types=1);

namespace VOSTPT\Http\Requests\User;

use Illuminate\Validation\Rule;
use VOSTPT\Http\Requests\Request;

class Update extends Request
{
    /**
     * {@inheritDoc}
     */
    public function authorize(): bool
    {
        $userToUpdate = $this->route('User');

        return $this->user()->can('update', $userToUpdate);
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
                    ->ignoreModel($this->route('User')),
            ],
            'password' => [
                'required_with:password_confirmation',
                'confirmed',
            ],
            'password_confirmation' => [
                'required_with:password',
            ],
            'roles' => [
                'array',
            ],
            'roles.*' => [
                Rule::exists('roles', 'name'),
            ],
        ];
    }
}
