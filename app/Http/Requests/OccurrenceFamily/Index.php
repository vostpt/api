<?php

declare(strict_types=1);

namespace VOSTPT\Http\Requests\OccurrenceFamily;

use Illuminate\Validation\Rule;
use VOSTPT\Filters\OccurrenceFamilyFilter;
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
            'sort' => [
                Rule::in(OccurrenceFamilyFilter::getSortableColumns()),
            ],
            'order' => [
                Rule::in(OccurrenceFamilyFilter::getOrderValues()),
            ],
        ];
    }
}
