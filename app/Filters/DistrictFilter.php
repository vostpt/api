<?php

declare(strict_types=1);

namespace VOSTPT\Filters;

class DistrictFilter extends Filter implements Contracts\DistrictFilter
{
    /**
     * {@inheritDoc}
     */
    public function getTable(): string
    {
        return 'districts';
    }

    /**
     * {@inheritDoc}
     */
    public static function getSearchableColumns(): array
    {
        return [
            'districts.code',
            'districts.name',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function getSortableColumns(): array
    {
        return [
            'code',
            'name',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getColumns(): array
    {
        return [
            'districts.id',
            'districts.code',
            'districts.name',
            'districts.created_at',
            'districts.updated_at',
        ];
    }
}
