<?php

declare(strict_types=1);

namespace VOSTPT\Http\Serializers;

use Tobscure\JsonApi\AbstractSerializer;
use Tobscure\JsonApi\Relationship;
use Tobscure\JsonApi\Resource;
use VOSTPT\Models\Occurrence;
use VOSTPT\Models\ProCivOccurrence;

class OccurrenceSerializer extends AbstractSerializer
{
    /**
     * {@inheritDoc}
     */
    protected $type = 'occurrences';

    /**
     * {@inheritDoc}
     */
    public function getLinks($model): array
    {
        return [
            'self' => route('occurrences::view', [
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
            'locality'   => $model->locality,
            'latitude'   => (float) $model->latitude,
            'longitude'  => (float) $model->longitude,
            'started_at' => $model->started_at->toDateTimeString(),
            'ended_at'   => $model->ended_at ? $model->ended_at->toDateTimeString() : null,
            'created_at' => $model->created_at->toDateTimeString(),
            'updated_at' => $model->updated_at->toDateTimeString(),
        ];
    }

    /**
     * Associated Event.
     *
     * @param Occurrence $occurrence
     *
     * @return \Tobscure\JsonApi\Relationship
     */
    public function event(Occurrence $occurrence): Relationship
    {
        return new Relationship(new Resource($occurrence->event()->first(), new EventSerializer()));
    }

    /**
     * Associated Parish.
     *
     * @param Occurrence $occurrence
     *
     * @return \Tobscure\JsonApi\Relationship
     */
    public function parish(Occurrence $occurrence): Relationship
    {
        return new Relationship(new Resource($occurrence->parish()->first(), new ParishSerializer()));
    }

    /**
     * Associated Source.
     *
     * @param Occurrence $occurrence
     *
     * @return \Tobscure\JsonApi\Relationship
     */
    public function source(Occurrence $occurrence): Relationship
    {
        $source = $occurrence->source()->first();

        if ($source instanceof ProCivOccurrence) {
            $serializer = new ProCivOccurrenceSerializer();
        }

        return new Relationship(new Resource($source, $serializer));
    }
}
