<?php

declare(strict_types=1);

namespace VOSTPT\Filters;

class CountyFilter extends Filter implements Contracts\CountyFilter
{
    /**
     * {@inheritDoc}
     */
    public function getTable(): string
    {
        return 'counties';
    }

    /**
     * {@inheritDoc}
     */
    public static function getSearchableColumns(): array
    {
        return [
            'counties.code',
            'counties.name',
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
            'counties.id',
            'counties.code',
            'counties.name',
            'counties.created_at',
            'counties.updated_at',
        ];
    }
}
