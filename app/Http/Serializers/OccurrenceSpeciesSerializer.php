<?php

declare(strict_types=1);

namespace VOSTPT\Http\Serializers;

use Tobscure\JsonApi\AbstractSerializer;
use Tobscure\JsonApi\Relationship;
use Tobscure\JsonApi\Resource;
use VOSTPT\Models\OccurrenceSpecies;

class OccurrenceSpeciesSerializer extends AbstractSerializer
{
    /**
     * {@inheritDoc}
     */
    protected $type = 'occurrence_species';

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
     * Associated Family.
     *
     * @param OccurrenceSpecies $occurrenceSpecies
     *
     * @return \Tobscure\JsonApi\Relationship
     */
    public function family(OccurrenceSpecies $occurrenceSpecies): Relationship
    {
        return new Relationship(new Resource($occurrenceSpecies->family()->first(), new OccurrenceFamilySerializer()));
    }
}
