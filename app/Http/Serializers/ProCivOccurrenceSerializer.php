<?php

declare(strict_types=1);

namespace VOSTPT\Http\Serializers;

use Tobscure\JsonApi\AbstractSerializer;
use Tobscure\JsonApi\Relationship;
use Tobscure\JsonApi\Resource;
use VOSTPT\Models\ProCivOccurrence;

class ProCivOccurrenceSerializer extends AbstractSerializer
{
    /**
     * {@inheritDoc}
     */
    protected $type = 'prociv_occurrences';

    /**
     * {@inheritDoc}
     */
    public function getLinks($model): array
    {
        return [
            'self' => route('occurrences::prociv::view', [
                'occurrence' => $model->getKey(),
            ]),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes($model, array $fields = null): array
    {
        return [
            'remote_id'                  => $model->remote_id,
            'ground_assets_involved'     => (int) $model->ground_assets_involved,
            'ground_operatives_involved' => (int) $model->ground_operatives_involved,
            'aerial_assets_involved'     => (int) $model->aerial_assets_involved,
            'aerial_operatives_involved' => (int) $model->aerial_operatives_involved,
            'created_at'                 => $model->created_at->toDateTimeString(),
            'updated_at'                 => $model->updated_at->toDateTimeString(),
        ];
    }

    /**
     * Associated Type.
     *
     * @param ProCivOccurrence $proCivOccurrence
     *
     * @return \Tobscure\JsonApi\Relationship
     */
    public function type(ProCivOccurrence $proCivOccurrence): Relationship
    {
        return new Relationship(new Resource($proCivOccurrence->type()->first(), new ProCivOccurrenceTypeSerializer()));
    }

    /**
     * Associated Status.
     *
     * @param ProCivOccurrence $proCivOccurrence
     *
     * @return \Tobscure\JsonApi\Relationship
     */
    public function status(ProCivOccurrence $proCivOccurrence): Relationship
    {
        return new Relationship(new Resource($proCivOccurrence->status()->first(), new ProCivOccurrenceStatusSerializer()));
    }
}
