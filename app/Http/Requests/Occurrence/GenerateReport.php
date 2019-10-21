<?php

declare(strict_types=1);

namespace VOSTPT\Http\Requests\Occurrence;

use Illuminate\Validation\Rule;
use VOSTPT\Filters\OccurrenceFilter;
use VOSTPT\Http\Requests\Request;

class GenerateReport extends Request
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        $rules = [
            'ids' => [
                'array',
            ],
            'ids.*' => [
                Rule::exists('occurrences', 'id'),
            ],
            'search' => [
                'string',
            ],
            'exact' => [
                'boolean',
            ],
            'events' => [
                'array',
            ],
            'events.*' => [
                Rule::exists('events', 'id'),
            ],
            'types' => [
                'array',
            ],
            'types.*' => [
                Rule::exists('occurrence_types', 'id'),
            ],
            'statuses' => [
                'array',
            ],
            'statuses.*' => [
                Rule::exists('occurrence_statuses', 'id'),
            ],
            'districts' => [
                'array',
            ],
            'districts.*' => [
                Rule::exists('districts', 'id'),
            ],
            'counties' => [
                'array',
            ],
            'counties.*' => [
                Rule::exists('counties', 'id'),
            ],
            'parishes' => [
                'array',
            ],
            'parishes.*' => [
                Rule::exists('parishes', 'id'),
            ],
            'started_at' => [
                'date_format:Y-m-d',
            ],
            'ended_at' => [
                'date_format:Y-m-d',
            ],
            'sort' => [
                Rule::in(OccurrenceFilter::getSortableColumns()),
            ],
            'order' => [
                Rule::in(OccurrenceFilter::getOrderValues()),
            ],
        ];

        if ($this->has('started_at', 'ended_at')) {
            $rules = \array_merge_recursive($rules, [
                'started_at' => [
                    'before_or_equal:ended_at',
                ],
                'ended_at' => [
                    'after_or_equal:started_at',
                ],
            ]);
        }

        return $rules;
    }
}
