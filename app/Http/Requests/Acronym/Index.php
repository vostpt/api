<?php

declare(strict_types=1);

namespace VOSTPT\Http\Requests\Acronym;

use Illuminate\Validation\Rule;
use VOSTPT\Filters\AcronymFilter;
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
                Rule::in(AcronymFilter::getSortableColumns()),
            ],
            'order' => [
                Rule::in(AcronymFilter::getOrderValues()),
            ],
        ];
    }
}
