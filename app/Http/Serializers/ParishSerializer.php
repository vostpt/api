<?php

declare(strict_types=1);

namespace VOSTPT\Http\Serializers;

use Tobscure\JsonApi\AbstractSerializer;
use Tobscure\JsonApi\Relationship;
use Tobscure\JsonApi\Resource;
use VOSTPT\Models\Parish;

class ParishSerializer extends AbstractSerializer
{
    /**
     * {@inheritDoc}
     */
    protected $type = 'parishes';

    /**
     * {@inheritDoc}
     */
    public function getLinks($model): array
    {
        return [
            'self' => route('parishes::view', [
                'parish' => $model->getKey(),
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
     * Associated County.
     *
     * @param Parish $parish
     *
     * @return \Tobscure\JsonApi\Relationship
     */
    public function county(Parish $parish): Relationship
    {
        return new Relationship(new Resource($parish->county()->first(), new CountySerializer()));
    }
}
