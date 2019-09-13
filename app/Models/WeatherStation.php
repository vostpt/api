<?php

declare(strict_types=1);

namespace VOSTPT\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeatherStation extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $table = 'weather_stations';

    /**
     * Associated County.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function county(): BelongsTo
    {
        return $this->belongsTo(County::class);
    }

    /**
     * Associated WeatherObservations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function observations(): HasMany
    {
        return $this->hasMany(WeatherObservation::class, 'station_id');
    }
}
