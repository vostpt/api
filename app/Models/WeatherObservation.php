<?php

declare(strict_types=1);

namespace VOSTPT\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeatherObservation extends Model
{
    use Concerns\Cacheable;

    /**
     * {@inheritDoc}
     */
    protected $table = 'weather_observations';

    /**
     * {@inheritDoc}
     */
    protected $dates = [
        'timestamp',
    ];

    /**
     * IPMA reading error / non observed value.
     */
    private const READING_ERROR = -99.0;

    /**
     * IPMA Wind direction map.
     */
    public const WIND_DIRECTIONS = [
        0 => null,
        1 => 'N',
        2 => 'NE',
        3 => 'E',
        4 => 'SE',
        5 => 'S',
        6 => 'SW',
        7 => 'W',
        8 => 'NW',
        9 => 'N',
    ];

    /**
     * Associated WeatherStation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function station(): BelongsTo
    {
        return $this->belongsTo(WeatherStation::class, 'station_id');
    }

    /**
     * Set the temperature.
     *
     * @param float $temperature
     *
     * @return void
     */
    public function setTemperatureAttribute(float $temperature): void
    {
        $this->attributes['temperature'] = $temperature === static::READING_ERROR ? null : $temperature;
    }

    /**
     * Set the humidity.
     *
     * @param float $humidity
     *
     * @return void
     */
    public function setHumidityAttribute(float $humidity): void
    {
        $this->attributes['humidity'] = $humidity === static::READING_ERROR ? null : $humidity;
    }

    /**
     * Set the wind speed.
     *
     * @param float $speed
     *
     * @return void
     */
    public function setWindSpeedAttribute(float $speed): void
    {
        $this->attributes['wind_speed'] = $speed === static::READING_ERROR ? null : $speed;
    }

    /**
     * Set the wind direction.
     *
     * @param string $direction
     *
     * @throws \DomainException
     *
     * @return void
     */
    public function setWindDirectionAttribute(string $direction = null): void
    {
        if (! \in_array($direction, static::WIND_DIRECTIONS, true)) {
            throw new \DomainException(\sprintf(
                'The wind direction value must be one of: %s',
                \implode(', ', static::WIND_DIRECTIONS)
            ));
        }

        $this->attributes['wind_direction'] = $direction;
    }

    /**
     * Set the precipitation.
     *
     * @param float $precipitation
     *
     * @return void
     */
    public function setPrecipitationAttribute(float $precipitation): void
    {
        $this->attributes['precipitation'] = $precipitation === static::READING_ERROR ? null : $precipitation;
    }

    /**
     * Set the atmospheric pressure.
     *
     * @param float $pressure
     *
     * @return void
     */
    public function setAtmosphericPressureAttribute(float $pressure): void
    {
        $this->attributes['atmospheric_pressure'] = $pressure === static::READING_ERROR ? null : $pressure;
    }

    /**
     * Set the radiation.
     *
     * @param float $radiation
     *
     * @return void
     */
    public function setRadiationAttribute(float $radiation): void
    {
        $this->attributes['radiation'] = $radiation === static::READING_ERROR ? null : $radiation;
    }
}
