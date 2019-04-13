<?php

declare(strict_types=1);

namespace VOSTPT\Filters;

class AcronymFilter extends Filter implements Contracts\AcronymFilter
{
    /**
     * {@inheritDoc}
     */
    public function getTable(): string
    {
        return 'acronyms';
    }

    /**
     * {@inheritDoc}
     */
    public static function getSearchableColumns(): array
    {
        return [
            'acronyms.initials',
            'acronyms.meaning',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function getSortableColumns(): array
    {
        return [
            'initials',
            'meaning',
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
            'acronyms.id',
            'acronyms.initials',
            'acronyms.meaning',
            'acronyms.created_at',
            'acronyms.updated_at',
        ];
    }
}
