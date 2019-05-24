<?php

declare(strict_types=1);

namespace VOSTPT\Filters;

class OccurrenceFamilyFilter extends Filter implements Contracts\OccurrenceFamilyFilter
{
    /**
     * {@inheritDoc}
     */
    public function getTable(): string
    {
        return 'occurrence_families';
    }

    /**
     * {@inheritDoc}
     */
    public static function getSearchableColumns(): array
    {
        return [
            'occurrence_families.id',
            'occurrence_families.code',
            'occurrence_families.name',
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
            'occurrence_families.id',
            'occurrence_families.code',
            'occurrence_families.name',
            'occurrence_families.created_at',
            'occurrence_families.updated_at',
        ];
    }
}
