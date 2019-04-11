<?php

declare(strict_types=1);

namespace VOSTPT\Http\Serializers;

use Tobscure\JsonApi\AbstractSerializer;
use Tobscure\JsonApi\Relationship;
use Tobscure\JsonApi\Resource;
use VOSTPT\Models\County;

class CountySerializer extends AbstractSerializer
{
    /**
     * {@inheritDoc}
     */
    protected $type = 'counties';

    /**
     * {@inheritDoc}
     */
    public function getLinks($model): array
    {
        return [
            'self' => route('counties::view', [
                'county' => $model->id,
            ]),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes($model, array $fields = null): array
    {
        return [
            'code'       => $model->code,
            'name'       => $model->name,
            'created_at' => $model->created_at->toDateTimeString(),
            'updated_at' => $model->updated_at->toDateTimeString(),
        ];
    }

    /**
     * Associated District.
     *
     * @param County $county
     *
     * @return \Tobscure\JsonApi\Relationship
     */
    public function district(County $county): Relationship
    {
        return new Relationship(new Resource($county->district()->first(), new DistrictSerializer()));
    }
}
