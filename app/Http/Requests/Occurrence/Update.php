<?php

declare(strict_types=1);

namespace VOSTPT\Http\Requests\Occurrence;

use Illuminate\Validation\Rule;
use VOSTPT\Http\Requests\Request;

class Update extends Request
{
    /**
     * {@inheritDoc}
     */
    public function authorize(): bool
    {
        $occurrence = $this->route('Occurrence');

        return $this->user()->can('update', $occurrence);
    }

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'event' => [
                'nullable',
                Rule::exists('events', 'id'),
            ],
        ];
    }
}
