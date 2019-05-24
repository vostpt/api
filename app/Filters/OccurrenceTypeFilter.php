<?php

declare(strict_types=1);

namespace VOSTPT\Filters;

use Illuminate\Database\Eloquent\Builder;

class OccurrenceTypeFilter extends Filter implements Contracts\OccurrenceTypeFilter
{
    /**
     * Species for filtering.
     *
     * @var array
     */
    private $species = [];

    /**
     * {@inheritDoc}
     */
    public function getTable(): string
    {
        return 'occurrence_types';
    }

    /**
     * {@inheritDoc}
     */
    public static function getSearchableColumns(): array
    {
        return [
            'occurrence_types.id',
            'occurrence_types.code',
            'occurrence_types.name',
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
            'occurrence_types.id',
            'occurrence_types.species_id',
            'occurrence_types.code',
            'occurrence_types.name',
            'occurrence_types.created_at',
            'occurrence_types.updated_at',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function withSpecies(...$species): Contracts\OccurrenceTypeFilter
    {
        $this->species = \array_unique($species, SORT_NUMERIC);

        \sort($this->species, SORT_NUMERIC);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Builder $builder): void
    {
        parent::apply($builder);

        // Apply Species filtering
        if ($this->species) {
            $builder->whereIn('species_id', $this->species);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getSignatureElements(): array
    {
        return \array_merge(parent::getSignatureElements(), [
            \implode(',', $this->species),
        ]);
    }
}
