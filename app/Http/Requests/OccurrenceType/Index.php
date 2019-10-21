<?php

declare(strict_types=1);

namespace VOSTPT\Http\Requests\OccurrenceType;

use Illuminate\Validation\Rule;
use VOSTPT\Filters\OccurrenceTypeFilter;
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
                Rule::exists('occurrence_types', 'id'),
            ],
            'search' => [
                'string',
            ],
            'exact' => [
                'boolean',
            ],
            'species' => [
                'array',
            ],
            'species.*' => [
                Rule::exists('occurrence_species', 'id'),
            ],
            'sort' => [
                Rule::in(OccurrenceTypeFilter::getSortableColumns()),
            ],
            'order' => [
                Rule::in(OccurrenceTypeFilter::getOrderValues()),
            ],
        ];
    }
}
