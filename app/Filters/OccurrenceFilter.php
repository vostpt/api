<?php

declare(strict_types=1);

namespace VOSTPT\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class OccurrenceFilter extends Filter implements Contracts\OccurrenceFilter
{
    use Concerns\GeoLocator;

    /**
     * Events for filtering.
     *
     * @var array
     */
    private $events = [];

    /**
     * Types for filtering.
     *
     * @var array
     */
    private $types = [];

    /**
     * Statuses for filtering.
     *
     * @var array
     */
    private $statuses = [];

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
     * Started at date filtering.
     *
     * @var Carbon
     */
    private $startedAt;

    /**
     * Ended at date filtering.
     *
     * @var Carbon
     */
    private $endedAt;

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
    public function withTypes(...$types): Contracts\OccurrenceFilter
    {
        $this->types = \array_unique($types, SORT_NUMERIC);

        \sort($this->types, SORT_NUMERIC);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function withStatuses(...$statuses): Contracts\OccurrenceFilter
    {
        $this->statuses = \array_unique($statuses, SORT_NUMERIC);

        \sort($this->statuses, SORT_NUMERIC);

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
    public function withStartedAt(Carbon $startedAt): Contracts\OccurrenceFilter
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function withEndedAt(Carbon $endedAt): Contracts\OccurrenceFilter
    {
        $this->endedAt = $endedAt;

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

        // Apply Type filtering
        if ($this->types) {
            $builder->whereIn('type_id', $this->types);
        }

        // Apply Status filtering
        if ($this->statuses) {
            $builder->whereIn('status_id', $this->statuses);
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

        // Apply Haversine formula
        $this->applyHaversine($builder);

        // Apply started at date filtering
        if ($this->startedAt) {
            $builder->whereDate('started_at', '>=', $this->startedAt->toDateString());
        }

        // Apply ended at date filtering
        if ($this->endedAt) {
            $builder->whereDate('ended_at', '<=', $this->endedAt->toDateString());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getSignatureElements(): array
    {
        return \array_merge(parent::getSignatureElements(), [
            \implode(',', $this->events),
            \implode(',', $this->types),
            \implode(',', $this->statuses),
            \implode(',', $this->districts),
            \implode(',', $this->counties),
            \implode(',', $this->parishes),
            $this->latitude,
            $this->longitude,
            $this->radius,
            $this->startedAt ? $this->startedAt->toDateString() : null,
            $this->endedAt ? $this->endedAt->toDateString() : null,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getUrlParameters(): array
    {
        $parameters = [];

        if ($this->events) {
            $parameters['events'] = $this->events;
        }

        if ($this->types) {
            $parameters['types'] = $this->types;
        }

        if ($this->statuses) {
            $parameters['statuses'] = $this->statuses;
        }

        if ($this->districts) {
            $parameters['districts'] = $this->districts;
        }

        if ($this->counties) {
            $parameters['counties'] = $this->counties;
        }

        if ($this->parishes) {
            $parameters['parishes'] = $this->parishes;
        }

        if ($this->latitude && $this->longitude) {
            $parameters['latitude']  = $this->latitude;
            $parameters['longitude'] = $this->longitude;
            $parameters['radius']    = $this->radius;
        }

        if ($this->startedAt) {
            $parameters['started_at'] = $this->startedAt->toDateString();
        }

        if ($this->endedAt) {
            $parameters['ended_at'] = $this->endedAt->toDateString();
        }

        return \array_merge(parent::getUrlParameters(), $parameters);
    }
}
