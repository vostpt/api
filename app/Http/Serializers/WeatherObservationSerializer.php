<?php

declare(strict_types=1);

namespace VOSTPT\Http\Serializers;

use Tobscure\JsonApi\AbstractSerializer;
use Tobscure\JsonApi\Relationship;
use Tobscure\JsonApi\Resource;
use VOSTPT\Models\WeatherObservation;

class WeatherObservationSerializer extends AbstractSerializer
{
    /**
     * {@inheritDoc}
     */
    protected $type = 'weather_observations';

    /**
     * {@inheritDoc}
     */
    public function getAttributes($model, array $fields = null): array
    {
        return [
            'temperature'          => $model->temperature,
            'humidity'             => $model->humidity,
            'wind_speed'           => $model->wind_speed,
            'wind_direction'       => $model->wind_direction,
            'precipitation'        => $model->precipitation,
            'atmospheric_pressure' => $model->atmospheric_pressure,
            'radiation'            => $model->radiation,
            'timestamp'            => $model->timestamp->toDateTimeString(),
            'created_at'           => $model->created_at->toDateTimeString(),
            'updated_at'           => $model->updated_at->toDateTimeString(),
        ];
    }

    /**
     * Associated WeatherStation.
     *
     * @param WeatherObservation $weatherObservation
     *
     * @return \Tobscure\JsonApi\Relationship
     */
    public function station(WeatherObservation $weatherObservation): Relationship
    {
        return new Relationship(new Resource($weatherObservation->station()->first(), new WeatherStationSerializer()));
    }
}
