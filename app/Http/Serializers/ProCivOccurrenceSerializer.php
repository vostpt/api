<?php

declare(strict_types=1);

namespace VOSTPT\Http\Serializers;

use Tobscure\JsonApi\AbstractSerializer;

class ProCivOccurrenceSerializer extends AbstractSerializer
{
    /**
     * {@inheritDoc}
     */
    protected $type = 'prociv_occurrences';

    /**
     * {@inheritDoc}
     */
    public function getAttributes($model, array $fields = null): array
    {
        return [
            'remote_id'         => $model->remote_id,
            'ground_assets'     => (int) $model->ground_assets,
            'ground_operatives' => (int) $model->ground_operatives,
            'aerial_assets'     => (int) $model->aerial_assets,
            'aerial_operatives' => (int) $model->aerial_operatives,
            'created_at'        => $model->created_at->toDateTimeString(),
            'updated_at'        => $model->updated_at->toDateTimeString(),
        ];
    }
}
