<?php

declare(strict_types=1);

namespace VOSTPT\Http\Requests\WeatherObservation;

use Illuminate\Validation\Rule;
use VOSTPT\Filters\WeatherObservationFilter;
use VOSTPT\Http\Requests\Request;

class Index extends Request
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        $rules = [
            'page.number' => [
                'integer',
            ],
            'page.size' => [
                'integer',
            ],
            'ids.*' => [
                Rule::exists('weather_observations', 'id'),
            ],
            'search' => [
                'string',
            ],
            'exact' => [
                'boolean',
            ],
            'districts.*' => [
                Rule::exists('districts', 'id'),
            ],
            'counties.*' => [
                Rule::exists('counties', 'id'),
            ],
            'stations.*' => [
                Rule::exists('weather_stations', 'id'),
            ],
            'timestamp_from' => [
                'date_format:Y-m-d',
            ],
            'timestamp_to' => [
                'date_format:Y-m-d',
            ],
            'sort' => [
                Rule::in(WeatherObservationFilter::getSortableColumns()),
            ],
            'order' => [
                Rule::in(WeatherObservationFilter::getOrderValues()),
            ],
        ];

        if ($this->has('timestamp_from', 'timestamp_to')) {
            $rules = \array_merge_recursive($rules, [
                'timestamp_from' => [
                    'before_or_equal:timestamp_to',
                ],
                'timestamp_to' => [
                    'after_or_equal:timestamp_from',
                ],
            ]);
        }

        return $rules;
    }
}
