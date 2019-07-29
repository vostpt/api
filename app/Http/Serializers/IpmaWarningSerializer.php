<?php

declare(strict_types=1);

namespace VOSTPT\Http\Serializers;

use Tobscure\JsonApi\AbstractSerializer;
use Tobscure\JsonApi\Relationship;
use Tobscure\JsonApi\Resource;
use VOSTPT\Models\County;
use VOSTPT\Models\District;

class IpmaWarningSerializer extends AbstractSerializer
{
    /**
     * {@inheritDoc}
     */
    protected $type = 'ipma_warnings';

    public function getId($item)
    {
        return $item['id'];
    }

    /**
     * {@inheritDoc}
     */
    public function getLinks($item): array
    {
        return [
            'county' => route('counties::view', [
                'County' => $item['county_id'],
            ]),
            'district' => route('districts::view', [
                'District' => $item['district_id'],
            ]),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes($item, array $fields = null): array
    {
        return [
            'description'         => $item['text'],
            'awareness_type_name' => $item['awarenessTypeName'],
            'awareness_level_id'  => \mb_strtoupper($item['awarenessLevelID']),
            'started_at'          => $item['started_at'],
            'ended_at'            => $item['ended_at'],
        ];
    }

    /**
     * Associated County.
     *
     * @param $item
     *
     * @return \Tobscure\JsonApi\Relationship
     */
    public function county($item): Relationship
    {
        $county = County::find($item['county_id']);
        return new Relationship(new Resource($county, new CountySerializer()));
    }

    /**
     * Associated District.
     *
     * @param $item
     *
     * @return \Tobscure\JsonApi\Relationship
     */
    public function district($item): Relationship
    {
        $district = District::find($item['district_id']);
        return new Relationship(new Resource($district, new DistrictSerializer()));
    }
}
