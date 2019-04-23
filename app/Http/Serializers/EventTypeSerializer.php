<?php

declare(strict_types=1);

namespace VOSTPT\Http\Serializers;

use Tobscure\JsonApi\AbstractSerializer;

class EventTypeSerializer extends AbstractSerializer
{
    /**
     * {@inheritDoc}
     */
    protected $type = 'event_types';

    /**
     * {@inheritDoc}
     */
    public function getAttributes($model, array $fields = null): array
    {
        return [
            'name'       => $model->name,
            'created_at' => $model->created_at->toDateTimeString(),
            'updated_at' => $model->updated_at->toDateTimeString(),
        ];
    }
}
