<?php

declare(strict_types=1);

namespace VOSTPT\Http\Serializers;

use Tobscure\JsonApi\AbstractSerializer;
use Tobscure\JsonApi\Relationship;
use Tobscure\JsonApi\Resource;

class WarningSerializer extends AbstractSerializer
{
    /**
     * {@inheritDoc}
     */
    protected $type = 'warnings';

    /**
     * {@inheritDoc}
     */
    public function getId($warning)
    {
        return $warning['id'];
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes($warning, array $fields = null): array
    {
        return [
            'description'         => $warning['text'],
            'awareness_type_name' => $warning['awarenessTypeName'],
            'awareness_level'     => $warning['awarenessLevelID'],
            'started_at'          => $warning['started_at']->toDateTimeString(),
            'ended_at'            => $warning['ended_at']->toDateTimeString(),
        ];
    }

    /**
     * Associated County.
     *
     * @param array $warning
     *
     * @return \Tobscure\JsonApi\Relationship
     */
    public function county(array $warning): Relationship
    {
        return new Relationship(new Resource($warning['county'], new CountySerializer()));
    }
}
