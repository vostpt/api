<?php

declare(strict_types=1);

namespace VOSTPT\Http\Requests\Event;

use Illuminate\Validation\Rule;
use VOSTPT\Http\Requests\Request;
use VOSTPT\Models\Event;

class Create extends Request
{
    /**
     * {@inheritDoc}
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Event::class);
    }

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'required',
                'string',
            ],
            'latitude' => [
                'required',
                'numeric',
            ],
            'longitude' => [
                'required',
                'numeric',
            ],
            'started_at' => [
                'required',
                'date_format:Y-m-d H:i:s',
            ],
            'ended_at' => [
                'nullable',
                'date_format:Y-m-d H:i:s',
            ],
            'type' => [
                'required',
                Rule::exists('event_types', 'id'),
            ],
            'parish' => [
                'required',
                Rule::exists('parishes', 'id'),
            ],
        ];

        if ($this->has('ended_at')) {
            $rules = \array_merge_recursive($rules, [
                'started_at' => [
                    'required',
                    'date_format:Y-m-d H:i:s',
                    'before_or_equal:ended_at',
                ],
                'ended_at' => [
                    'date_format:Y-m-d H:i:s',
                    'after_or_equal:started_at',
                ],
            ]);
        }

        return $rules;
    }
}
