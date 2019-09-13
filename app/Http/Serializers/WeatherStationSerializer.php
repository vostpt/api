<?php

declare(strict_types=1);

namespace VOSTPT\Http\Serializers;

use Tobscure\JsonApi\AbstractSerializer;
use Tobscure\JsonApi\Relationship;
use Tobscure\JsonApi\Resource;
use VOSTPT\Models\WeatherStation;

class WeatherStationSerializer extends AbstractSerializer
{
    /**
     * {@inheritDoc}
     */
    protected $type = 'weather_stations';

    /**
     * {@inheritDoc}
     */
    public function getAttributes($model, array $fields = null): array
    {
        return [
            'entity'     => $model->entity,
            'name'       => $model->name,
            'serial'     => $model->serial,
            'created_at' => $model->created_at->toDateTimeString(),
            'updated_at' => $model->updated_at->toDateTimeString(),
        ];
    }

    /**
     * Associated County.
     *
     * @param WeatherStation $weatherStation
     *
     * @return \Tobscure\JsonApi\Relationship
     */
    public function county(WeatherStation $weatherStation): Relationship
    {
        return new Relationship(new Resource($weatherStation->county()->first(), new CountySerializer()));
    }
}
