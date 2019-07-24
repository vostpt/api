<?php

declare(strict_types=1);

namespace VOSTPT\Http\Requests\Occurrence;

use Illuminate\Validation\Rule;
use VOSTPT\Filters\OccurrenceFilter;
use VOSTPT\Http\Requests\Request;

class Index extends Request
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            'page.number' => [
                'integer',
            ],
            'page.size' => [
                'integer',
            ],
            'search' => [
                'string',
            ],
            'exact' => [
                'boolean',
            ],
            'events.*' => [
                Rule::exists('events', 'id'),
            ],
            'types.*' => [
                Rule::exists('occurrence_types', 'id'),
            ],
            'statuses.*' => [
                Rule::exists('occurrence_statuses', 'id'),
            ],
            'districts.*' => [
                Rule::exists('districts', 'id'),
            ],
            'counties.*' => [
                Rule::exists('counties', 'id'),
            ],
            'parishes.*' => [
                Rule::exists('parishes', 'id'),
            ],
            'sort' => [
                Rule::in(OccurrenceFilter::getSortableColumns()),
            ],
            'order' => [
                Rule::in(OccurrenceFilter::getOrderValues()),
            ],
        ];
    }
}
