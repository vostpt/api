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

    public function getId($model)
    {
        return $model['id'];
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes($model, array $fields = null): array
    {
        return [
            'description'         => $model['text'],
            'awareness_type_name' => $model['awarenessTypeName'],
            'awareness_level_id'  => \mb_strtoupper($model['awarenessLevelID']),
            'region'              => $model['region'],
            'county'              => $model['county'],
            'started_at'          => $model['started_at'],
            'ended_at'            => $model['ended_at'],
        ];
    }
}
