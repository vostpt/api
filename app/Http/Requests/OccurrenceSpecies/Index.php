<?php

declare(strict_types=1);

namespace VOSTPT\Http\Requests\OccurrenceSpecies;

use Illuminate\Validation\Rule;
use VOSTPT\Filters\OccurrenceSpeciesFilter;
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
                Rule::exists('occurrence_species', 'id'),
            ],
            'search' => [
                'string',
            ],
            'exact' => [
                'boolean',
            ],
            'families.*' => [
                Rule::exists('occurrence_families', 'id'),
            ],
            'sort' => [
                Rule::in(OccurrenceSpeciesFilter::getSortableColumns()),
            ],
            'order' => [
                Rule::in(OccurrenceSpeciesFilter::getOrderValues()),
            ],
        ];
    }
}
