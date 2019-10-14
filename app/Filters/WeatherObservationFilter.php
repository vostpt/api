<?php

declare(strict_types=1);

namespace VOSTPT\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class WeatherObservationFilter extends Filter implements Contracts\WeatherObservationFilter
{
    /**
     * {@inheritDoc}
     */
    protected $pageSize = 100;

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
     * Weather Stations for filtering.
     *
     * @var array
     */
    private $stations = [];

    /**
     * From timestamp filtering.
     *
     * @var Carbon
     */
    private $timestampFrom;

    /**
     * To timestamp filtering.
     *
     * @var Carbon
     */
    private $timestampTo;

    /**
     * {@inheritDoc}
     */
    public function getTable(): string
    {
        return 'weather_observations';
    }

    /**
     * {@inheritDoc}
     */
    public static function getSearchableColumns(): array
    {
        return [
            'weather_observations.id',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function getSortableColumns(): array
    {
        return [
            'temperature',
            'humidity',
            'wind_speed',
            'wind_direction',
            'radiation',
            'timestamp',
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
            'weather_observations.id',
            'weather_observations.station_id',
            'weather_observations.temperature',
            'weather_observations.humidity',
            'weather_observations.wind_speed',
            'weather_observations.wind_direction',
            'weather_observations.radiation',
            'weather_observations.timestamp',
            'weather_observations.created_at',
            'weather_observations.updated_at',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function withDistricts(...$districts): Contracts\WeatherObservationFilter
    {
        $this->districts = \array_unique($districts, SORT_NUMERIC);

        \sort($this->districts, SORT_NUMERIC);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function withCounties(...$counties): Contracts\WeatherObservationFilter
    {
        $this->counties = \array_unique($counties, SORT_NUMERIC);

        \sort($this->counties, SORT_NUMERIC);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function withStations(...$stations): Contracts\WeatherObservationFilter
    {
        $this->stations = \array_unique($stations, SORT_NUMERIC);

        \sort($this->stations, SORT_NUMERIC);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function withTimestampFrom(Carbon $from): Contracts\WeatherObservationFilter
    {
        $this->timestampFrom = $from;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function withTimestampTo(Carbon $to): Contracts\WeatherObservationFilter
    {
        $this->timestampTo = $to;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Builder $builder): void
    {
        parent::apply($builder);

        // Apply common join statements
        if ($this->districts || $this->counties) {
            $builder->join('weather_stations', 'weather_stations.id', '=', 'weather_observations.station_id')
                ->join('counties', 'counties.id', '=', 'weather_stations.county_id');
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

        // Apply WeatherStations filtering
        if ($this->stations) {
            $builder->whereIn('station_id', $this->stations);
        }

        // Apply timestamp from filtering
        if ($this->timestampFrom) {
            $builder->whereDate('timestamp', '>=', $this->timestampFrom->toDateString());
        }

        // Apply timestamp to filtering
        if ($this->timestampTo) {
            $builder->whereDate('timestamp', '<=', $this->timestampTo->toDateString());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getSignatureElements(): array
    {
        return \array_merge(parent::getSignatureElements(), [
            \implode(',', $this->districts),
            \implode(',', $this->counties),
            \implode(',', $this->stations),
            $this->timestampFrom ? $this->timestampFrom->toDateString() : null,
            $this->timestampTo ? $this->timestampTo->toDateString() : null,
        ]);
    }
}
