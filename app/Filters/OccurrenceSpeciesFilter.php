<?php

declare(strict_types=1);

namespace VOSTPT\Filters;

use Illuminate\Database\Eloquent\Builder;

class OccurrenceSpeciesFilter extends Filter implements Contracts\OccurrenceSpeciesFilter
{
    /**
     * Families for filtering.
     *
     * @var array
     */
    private $families = [];

    /**
     * {@inheritDoc}
     */
    public function getTable(): string
    {
        return 'occurrence_species';
    }

    /**
     * {@inheritDoc}
     */
    public static function getSearchableColumns(): array
    {
        return [
            'occurrence_species.id',
            'occurrence_species.code',
            'occurrence_species.name',
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
            'occurrence_species.id',
            'occurrence_species.family_id',
            'occurrence_species.code',
            'occurrence_species.name',
            'occurrence_species.created_at',
            'occurrence_species.updated_at',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function withFamilies(...$families): Contracts\OccurrenceSpeciesFilter
    {
        $this->families = \array_unique($families, SORT_NUMERIC);

        \sort($this->families, SORT_NUMERIC);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Builder $builder): void
    {
        parent::apply($builder);

        // Apply Family filtering
        if ($this->families) {
            $builder->whereIn('family_id', $this->families);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getSignatureElements(): array
    {
        return \array_merge(parent::getSignatureElements(), [
            \implode(',', $this->families),
        ]);
    }
}
