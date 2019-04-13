<?php

declare(strict_types=1);

namespace VOSTPT\Http\Serializers;

use Tobscure\JsonApi\AbstractSerializer;

class AcronymSerializer extends AbstractSerializer
{
    /**
     * {@inheritDoc}
     */
    protected $type = 'acronyms';

    /**
     * {@inheritDoc}
     */
    public function getAttributes($model, array $fields = null): array
    {
        return [
            'initials'   => $model->initials,
            'meaning'    => $model->meaning,
            'created_at' => $model->created_at->toDateTimeString(),
            'updated_at' => $model->updated_at->toDateTimeString(),
        ];
    }
}
