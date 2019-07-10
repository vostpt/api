<?php

declare(strict_types=1);

namespace VOSTPT\Filters;

class OccurrenceStatusFilter extends Filter implements Contracts\OccurrenceStatusFilter
{
    /**
     * {@inheritDoc}
     */
    public function getTable(): string
    {
        return 'occurrence_statuses';
    }

    /**
     * {@inheritDoc}
     */
    public static function getSearchableColumns(): array
    {
        return [
            'occurrence_statuses.id',
            'occurrence_statuses.code',
            'occurrence_statuses.name',
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
            'occurrence_statuses.id',
            'occurrence_statuses.code',
            'occurrence_statuses.name',
            'occurrence_statuses.created_at',
            'occurrence_statuses.updated_at',
        ];
    }
}
