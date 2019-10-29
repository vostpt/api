<?php

declare(strict_types=1);

namespace VOSTPT\Filters;

use Illuminate\Database\Eloquent\Builder;

class EventFilter extends Filter implements Contracts\EventFilter
{
    use Concerns\GeoLocator;

    /**
     * Event types for filtering.
     *
     * @var array
     */
    private $types = [];

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
        return 'events';
    }

    /**
     * {@inheritDoc}
     */
    public static function getSearchableColumns(): array
    {
        return [
            'events.id',
            'events.name',
            'events.description',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function getSortableColumns(): array
    {
        return [
            'name',
            'description',
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
            'events.id',
            'events.name',
            'events.description',
            'events.latitude',
            'events.longitude',
            'events.started_at',
            'events.ended_at',
            'events.created_at',
            'events.updated_at',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function withTypes(...$types): Contracts\EventFilter
    {
        $this->types = \array_unique($types, SORT_NUMERIC);

        \sort($this->types, SORT_NUMERIC);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function withParishes(...$parishes): Contracts\EventFilter
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

        // Apply EventType filtering
        if ($this->types) {
            $builder->whereIn('type_id', $this->types);
        }

        // Apply Parish filtering
        if ($this->parishes) {
            $builder->whereIn('parish_id', $this->parishes);
        }

        // Apply Haversine formula
        $this->applyHaversine($builder);
    }

    /**
     * {@inheritDoc}
     */
    public function getSignatureElements(): array
    {
        return \array_merge(parent::getSignatureElements(), [
            \implode(',', $this->types),
            \implode(',', $this->parishes),
            $this->latitude,
            $this->longitude,
            $this->radius,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getUrlParameters(): array
    {
        $parameters = [];

        if ($this->types) {
            $parameters['types'] = $this->types;
        }

        if ($this->parishes) {
            $parameters['parishes'] = $this->parishes;
        }

        if ($this->latitude && $this->longitude) {
            $parameters['latitude']  = $this->latitude;
            $parameters['longitude'] = $this->longitude;
            $parameters['radius']    = $this->radius;
        }

        return \array_merge(parent::getUrlParameters(), $parameters);
    }
}
