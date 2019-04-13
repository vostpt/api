<?php

declare(strict_types=1);

namespace VOSTPT\Http\Requests\Acronym;

use Illuminate\Validation\Rule;
use VOSTPT\Http\Requests\Request;
use VOSTPT\Models\Acronym;

class Create extends Request
{
    /**
     * {@inheritDoc}
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Acronym::class);
    }

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'initials' => [
                'required',
                'string',
                'max:16',
                Rule::unique('acronyms', 'initials'),
            ],
            'meaning' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }
}
