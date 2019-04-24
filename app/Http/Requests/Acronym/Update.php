<?php

declare(strict_types=1);

namespace VOSTPT\Http\Requests\Acronym;

use Illuminate\Validation\Rule;
use VOSTPT\Http\Requests\Request;

class Update extends Request
{
    /**
     * {@inheritDoc}
     */
    public function authorize(): bool
    {
        $acronym = $this->route('Acronym');

        return $this->user()->can('update', $acronym);
    }

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'initials' => [
                'string',
                'max:16',
                Rule::unique('acronyms', 'initials')
                    ->ignoreModel($this->route('Acronym')),
            ],
            'meaning' => [
                'string',
                'max:255',
            ],
        ];
    }
}
