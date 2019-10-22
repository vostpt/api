<?php

declare(strict_types=1);

namespace VOSTPT\Http\Requests\County;

use Illuminate\Validation\Rule;
use VOSTPT\Filters\CountyFilter;
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
                Rule::exists('counties', 'id'),
            ],
            'search' => [
                'string',
            ],
            'exact' => [
                'boolean',
            ],
            'districts' => [
                'array',
            ],
            'districts.*' => [
                Rule::exists('districts', 'id'),
            ],
            'sort' => [
                Rule::in(CountyFilter::getSortableColumns()),
            ],
            'order' => [
                Rule::in(CountyFilter::getOrderValues()),
            ],
        ];
    }
}
