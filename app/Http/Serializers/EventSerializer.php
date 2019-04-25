<?php

declare(strict_types=1);

namespace VOSTPT\Http\Serializers;

use Tobscure\JsonApi\AbstractSerializer;
use Tobscure\JsonApi\Relationship;
use Tobscure\JsonApi\Resource;
use VOSTPT\Models\Event;

class EventSerializer extends AbstractSerializer
{
    /**
     * {@inheritDoc}
     */
    protected $type = 'events';

    /**
     * {@inheritDoc}
     */
    public function getLinks($model): array
    {
        return [
            'self' => route('events::view', [
                'event' => $model->getKey(),
            ]),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes($model, array $fields = null): array
    {
        return [
            'name'        => $model->name,
            'description' => $model->description,
            'latitude'    => (float) $model->latitude,
            'longitude'   => (float) $model->longitude,
            'started_at'  => $model->started_at->toDateTimeString(),
            'ended_at'    => $model->ended_at ? $model->ended_at->toDateTimeString() : null,
            'created_at'  => $model->created_at->toDateTimeString(),
            'updated_at'  => $model->updated_at->toDateTimeString(),
        ];
    }

    /**
     * Associated Type.
     *
     * @param Event $event
     *
     * @return \Tobscure\JsonApi\Relationship
     */
    public function type(Event $event): Relationship
    {
        return new Relationship(new Resource($event->type()->first(), new EventTypeSerializer()));
    }

    /**
     * Associated Parish.
     *
     * @param Event $event
     *
     * @return \Tobscure\JsonApi\Relationship
     */
    public function parish(Event $event): Relationship
    {
        return new Relationship(new Resource($event->parish()->first(), new ParishSerializer()));
    }
}
