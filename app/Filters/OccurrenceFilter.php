<?php

declare(strict_types=1);

namespace VOSTPT\Filters;

use Illuminate\Database\Eloquent\Builder;

class OccurrenceFilter extends Filter implements Contracts\OccurrenceFilter
{
    /**
     * Events for filtering.
     *
     * @var array
     */
    private $events = [];

    /**
     * Districts for filtering.
     *
     * @var array
     */
    private $districts = [];

    /**
     * Counties for filtering.
     *
     * @var array
     */
    private $counties = [];

    /**
     * Parishes for filtering.
     *
     * @var array
     */
    private $parishes = [];

    /**
     * {@inheritDoc}
     */
    public function getTable(): string
    {
        return 'occurrences';
    }

    /**
     * {@inheritDoc}
     */
    public static function getSearchableColumns(): array
    {
        return [
            'occurrences.id',
            'occurrences.locality',
            'occurrences.latitude',
            'occurrences.longitude',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function getSortableColumns(): array
    {
        return [
            'locality',
            'latitude',
            'longitude',
            'started_at',
            'ended_at',
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
            'occurrences.id',
            'occurrences.event_id',
            'occurrences.type_id',
            'occurrences.status_id',
            'occurrences.parish_id',
            'occurrences.locality',
            'occurrences.latitude',
            'occurrences.longitude',
            'occurrences.started_at',
            'occurrences.ended_at',
            'occurrences.created_at',
            'occurrences.updated_at',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function withEvents(...$events): Contracts\OccurrenceFilter
    {
        $this->events = \array_unique($events, SORT_NUMERIC);

        \sort($this->events, SORT_NUMERIC);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function withDistricts(...$districts): Contracts\OccurrenceFilter
    {
        $this->districts = \array_unique($districts, SORT_NUMERIC);

        \sort($this->districts, SORT_NUMERIC);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function withCounties(...$counties): Contracts\OccurrenceFilter
    {
        $this->counties = \array_unique($counties, SORT_NUMERIC);

        \sort($this->counties, SORT_NUMERIC);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function withParishes(...$parishes): Contracts\OccurrenceFilter
    {
        $this->parishes = \array_unique($parishes, SORT_NUMERIC);

        \sort($this->parishes, SORT_NUMERIC);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Builder $builder): void
    {
        parent::apply($builder);

        // Apply Event filtering
        if ($this->events) {
            $builder->whereIn('event_id', $this->events);
        }

        // Apply common join statements
        if ($this->districts || $this->counties) {
            $builder->join('parishes', 'parishes.id', '=', 'occurrences.parish_id')
                ->join('counties', 'counties.id', '=', 'parishes.county_id');
        }

        // Apply District filtering
        if ($this->districts) {
            $builder->join('districts', 'districts.id', '=', 'counties.district_id')
                ->whereIn('districts.id', $this->districts);
        }

        // Apply County filtering
        if ($this->counties) {
            $builder->whereIn('counties.id', $this->counties);
        }

        // Apply Parish filtering
        if ($this->parishes) {
            $builder->whereIn('parish_id', $this->parishes);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getSignatureElements(): array
    {
        return \array_merge(parent::getSignatureElements(), [
            \implode(',', $this->events),
            \implode(',', $this->districts),
            \implode(',', $this->counties),
            \implode(',', $this->parishes),
        ]);
    }
}
