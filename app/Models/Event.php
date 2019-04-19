<?php

declare(strict_types=1);

namespace VOSTPT\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use LengthException;

class Event extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $table = 'events';

    /**
     * {@inheritDoc}
     */
    protected $dates = [
        'started_at',
        'ended_at',
    ];

    /**
     * Associated Type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(EventType::class, 'type_id');
    }

    /**
     * Associated Parish.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parish(): BelongsTo
    {
        return $this->belongsTo(Parish::class);
    }

    /**
     * Associated Occurrences.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function occurrences(): HasMany
    {
        return $this->hasMany(Occurrence::class);
    }

    /**
     * Set the name.
     *
     * @param string $name
     *
     * @throws \LengthException
     *
     * @return void
     */
    public function setNameAttribute(string $name): void
    {
        if (\mb_strlen($name) > 255) {
            throw new LengthException('The name cannot exceed 255 characters');
        }

        $this->attributes['name'] = $name;
    }

    /**
     * Set the latitude.
     *
     * @param float $latitude
     *
     * @return void
     */
    public function setLatitudeAttribute(float $latitude): void
    {
        $this->attributes['latitude'] = $latitude;
    }

    /**
     * Set the longitude.
     *
     * @param float $longitude
     *
     * @return void
     */
    public function setLongitudeAttribute(float $longitude): void
    {
        $this->attributes['longitude'] = $longitude;
    }

    /**
     * Set the Started at value.
     *
     * @param DateTime $startedAt
     *
     * @return void
     */
    public function setStartedAtAttribute(DateTime $startedAt): void
    {
        $this->attributes['started_at'] = $startedAt;
    }

    /**
     * Set the Ended at value.
     *
     * @param DateTime $endedAt
     *
     * @return void
     */
    public function setEndedAtAttribute(DateTime $endedAt = null): void
    {
        $this->attributes['ended_at'] = $endedAt;
    }
}
