<?php

declare(strict_types=1);

namespace VOSTPT\Filters;

use Illuminate\Database\Eloquent\Builder;

class CountyFilter extends Filter implements Contracts\CountyFilter
{
    /**
     * Districts for filtering.
     *
     * @var array
     */
    private $districts = [];

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

    /**
     * {@inheritDoc}
     */
    public function withDistricts(...$districts): Contracts\CountyFilter
    {
        $this->districts = \array_unique($districts, SORT_NUMERIC);

        \sort($this->districts, SORT_NUMERIC);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Builder $builder): void
    {
        parent::apply($builder);

        // Apply District filtering
        if ($this->districts) {
            $builder->whereIn('district_id', $this->districts);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getSignatureElements(): array
    {
        return \array_merge(parent::getSignatureElements(), [
            \implode(',', $this->districts),
        ]);
    }
}
