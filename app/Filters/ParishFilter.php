<?php

declare(strict_types=1);

namespace VOSTPT\Filters;

use Illuminate\Database\Eloquent\Builder;

class ParishFilter extends Filter implements Contracts\ParishFilter
{
    /**
     * Counties for filtering.
     *
     * @var array
     */
    private $counties = [];

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

    /**
     * {@inheritDoc}
     */
    public function withCounties(...$counties): Contracts\ParishFilter
    {
        $this->counties = \array_unique($counties, SORT_NUMERIC);

        \sort($this->counties, SORT_NUMERIC);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Builder $builder): void
    {
        parent::apply($builder);

        // Apply County filtering
        if ($this->counties) {
            $builder->whereIn('county_id', $this->counties);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getSignatureElements(): array
    {
        return \array_merge(parent::getSignatureElements(), [
            \implode(',', $this->counties),
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getUrlParameters(): array
    {
        $parameters = [];

        if ($this->counties) {
            $parameters['counties'] = $this->counties;
        }

        return \array_merge(parent::getUrlParameters(), $parameters);
    }
}
