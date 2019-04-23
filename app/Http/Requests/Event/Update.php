<?php

declare(strict_types=1);

namespace VOSTPT\Http\Requests\Event;

use Illuminate\Validation\Rule;
use VOSTPT\Http\Requests\Request;

class Update extends Request
{
    /**
     * {@inheritDoc}
     */
    public function authorize(): bool
    {
        $event = $this->route('event');

        return $this->user()->can('update', $event);
    }

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        $rules = [
            'name' => [
                'string',
                'max:255',
            ],
            'description' => [
                'string',
            ],
            'latitude' => [
                'numeric',
            ],
            'longitude' => [
                'numeric',
            ],
            'started_at' => [
                'date_format:Y-m-d H:i:s',
            ],
            'ended_at' => [
                'nullable',
                'date_format:Y-m-d H:i:s',
            ],
            'type' => [
                Rule::exists('event_types', 'id'),
            ],
            'parish' => [
                Rule::exists('parishes', 'id'),
            ],
        ];

        if ($this->has('ended_at')) {
            $rules = \array_merge_recursive($rules, [
                'started_at' => [
                    'date_format:Y-m-d H:i:s',
                    'before_or_equal:ended_at',
                ],
                'ended_at' => [
                    'nullable',
                    'date_format:Y-m-d H:i:s',
                    'after_or_equal:started_at',
                ],
            ]);
        }

        return $rules;
    }
}
