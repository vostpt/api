<?php

declare(strict_types=1);

namespace VOSTPT\Http\Requests\OccurrenceStatus;

use Illuminate\Validation\Rule;
use VOSTPT\Filters\OccurrenceStatusFilter;
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
            'ids.*' => [
                Rule::exists('occurrence_statuses', 'id'),
            ],
            'search' => [
                'string',
            ],
            'exact' => [
                'boolean',
            ],
            'sort' => [
                Rule::in(OccurrenceStatusFilter::getSortableColumns()),
            ],
            'order' => [
                Rule::in(OccurrenceStatusFilter::getOrderValues()),
            ],
        ];
    }
}
