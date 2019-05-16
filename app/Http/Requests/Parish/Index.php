<?php

declare(strict_types=1);

namespace VOSTPT\Http\Requests\Parish;

use Illuminate\Validation\Rule;
use VOSTPT\Filters\ParishFilter;
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
            'counties.*' => [
                Rule::exists('counties', 'id'),
            ],
            'sort' => [
                Rule::in(ParishFilter::getSortableColumns()),
            ],
            'order' => [
                Rule::in(ParishFilter::getOrderValues()),
            ],
        ];
    }
}
