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
            'remote_id'                  => $model->remote_id,
            'ground_assets_involved'     => (int) $model->ground_assets_involved,
            'ground_operatives_involved' => (int) $model->ground_operatives_involved,
            'aerial_assets_involved'     => (int) $model->aerial_assets_involved,
            'aerial_operatives_involved' => (int) $model->aerial_operatives_involved,
            'created_at'                 => $model->created_at->toDateTimeString(),
            'updated_at'                 => $model->updated_at->toDateTimeString(),
        ];
    }
}
