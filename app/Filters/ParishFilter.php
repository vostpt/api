<?php

declare(strict_types=1);

namespace VOSTPT\Filters;

class ParishFilter extends Filter implements Contracts\ParishFilter
{
    /**
     * {@inheritDoc}
     */
    public function getTable(): string
    {
        return 'parishes';
    }

    /**
     * {@inheritDoc}
     */
    public static function getSearchableColumns(): array
    {
        return [
            'parishes.code',
            'parishes.name',
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
            'parishes.id',
            'parishes.code',
            'parishes.name',
            'parishes.created_at',
            'parishes.updated_at',
        ];
    }
}
