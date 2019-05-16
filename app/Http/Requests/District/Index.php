<?php

declare(strict_types=1);

namespace VOSTPT\Http\Requests\District;

use Illuminate\Validation\Rule;
use VOSTPT\Filters\DistrictFilter;
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
                Rule::in(DistrictFilter::getSortableColumns()),
            ],
            'order' => [
                Rule::in(DistrictFilter::getOrderValues()),
            ],
        ];
    }
}
