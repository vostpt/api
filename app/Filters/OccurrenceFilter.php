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
    public function withEvents(int ...$events): Contracts\OccurrenceFilter
    {
        $this->events = \array_unique($events, SORT_NUMERIC);

        \sort($this->events, SORT_NUMERIC);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function withParishes(int ...$parishes): Contracts\OccurrenceFilter
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
            \implode(',', $this->parishes),
        ]);
    }
}
