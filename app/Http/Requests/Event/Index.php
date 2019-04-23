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
            'search' => [
                'string',
            ],
            'types.*' => [
                Rule::exists('event_types', 'id'),
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
