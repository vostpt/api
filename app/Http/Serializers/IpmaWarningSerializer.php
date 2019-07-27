<?php

declare(strict_types=1);

namespace VOSTPT\Http\Serializers;

use Tobscure\JsonApi\AbstractSerializer;

class IpmaWarningSerializer extends AbstractSerializer
{
    /**
     * {@inheritDoc}
     */
    protected $type = 'ipma_warnings';

    /**
     * {@inheritDoc}
     */
    public function getAttributes($model, array $fields = null): array
    {
        return [
            'uuid'                => $model->uuid,
            'text'                => $model->text,
            'awareness_type_name' => $model->awareness_type_name,
            'awareness_level_id'  => $model->awareness_level_id,
            'id_area_warning'     => $model->id_area_warning,
            'area_name'           => $model->area_name,
            'started_at'          => $model->started_at,
            'ended_at'            => $model->ended_at,
            'created_at'          => $model->created_at->toDateTimeString(),
            'updated_at'          => $model->updated_at->toDateTimeString(),
        ];
    }
}
