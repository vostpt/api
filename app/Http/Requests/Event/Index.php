<?php

declare(strict_types=1);

namespace VOSTPT\Http\Requests\Event;

use Illuminate\Validation\Rule;
use VOSTPT\Filters\EventFilter;
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
            'ids' => [
                'array',
            ],
            'ids.*' => [
                Rule::exists('events', 'id'),
            ],
            'search' => [
                'string',
            ],
            'exact' => [
                'boolean',
            ],
            'types' => [
                'array',
            ],
            'types.*' => [
                Rule::exists('event_types', 'id'),
            ],
            'parishes' => [
                'array',
            ],
            'parishes.*' => [
                Rule::exists('parishes', 'id'),
            ],
            'sort' => [
                Rule::in(EventFilter::getSortableColumns()),
            ],
            'order' => [
                Rule::in(EventFilter::getOrderValues()),
            ],
        ];
    }
}
