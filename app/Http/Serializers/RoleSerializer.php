<?php

declare(strict_types=1);

namespace VOSTPT\Http\Serializers;

use Tobscure\JsonApi\AbstractSerializer;

class RoleSerializer extends AbstractSerializer
{
    /**
     * {@inheritDoc}
     */
    protected $type = 'roles';

    /**
     * {@inheritDoc}
     */
    public function getAttributes($model, array $fields = null): array
    {
        return [
            'name'       => $model->name,
            'title'      => $model->title,
            'level'      => $model->level,
            'scope'      => $model->scope,
            'created_at' => $model->created_at->toDateTimeString(),
            'updated_at' => $model->updated_at->toDateTimeString(),
        ];
    }
}
