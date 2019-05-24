<?php

declare(strict_types=1);

namespace VOSTPT\Http\Serializers;

use Tobscure\JsonApi\AbstractSerializer;
use Tobscure\JsonApi\Relationship;
use Tobscure\JsonApi\Resource;
use VOSTPT\Models\OccurrenceType;

class OccurrenceTypeSerializer extends AbstractSerializer
{
    /**
     * {@inheritDoc}
     */
    protected $type = 'occurrence_types';

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
     * Associated Species.
     *
     * @param OccurrenceType $occurrenceType
     *
     * @return \Tobscure\JsonApi\Relationship
     */
    public function species(OccurrenceType $occurrenceType): Relationship
    {
        return new Relationship(new Resource($occurrenceType->species()->first(), new OccurrenceSpeciesSerializer()));
    }
}
