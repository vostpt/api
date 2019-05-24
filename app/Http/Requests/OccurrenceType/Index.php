<?php

declare(strict_types=1);

namespace VOSTPT\Http\Requests\OccurrenceType;

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
            'species.*' => [
                Rule::exists('occurrence_species', 'id'),
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
