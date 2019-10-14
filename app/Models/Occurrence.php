<?php

declare(strict_types=1);

namespace VOSTPT\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use LengthException;

class Occurrence extends Model
{
    use Concerns\Cacheable;

    /**
     * {@inheritDoc}
     */
    protected $table = 'occurrences';

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
    ];

    /**
     * {@inheritDoc}
     */
    protected $dates = [
        'started_at',
        'ended_at',
    ];

    /**
     * Associated Event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Associated Type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(OccurrenceType::class, 'type_id');
    }

    /**
     * Associated Status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(OccurrenceStatus::class, 'status_id');
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
     * Associated source.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function source(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Set the locality.
     *
     * @param string $locality
     *
     * @throws \LengthException
     *
     * @return void
     */
    public function setNameAttribute(string $locality): void
    {
        if (\mb_strlen($locality) > 255) {
            throw new LengthException('The locality cannot exceed 255 characters');
        }

        $this->attributes['locality'] = $locality;
    }

    /**
     * Stalled query scope.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStalled(Builder $query): Builder
    {
        // An Occurrence is considered stalled, when the state isn't
        // set to closed and the last update was at least 1h ago
        return $query->whereHas('status', function ($query) {
            $query->whereNotIn('code', [
                OccurrenceStatus::CLOSED,
                OccurrenceStatus::CLOSED_BY_VOST,
            ]);
        })
        ->where('updated_at', '<=', Carbon::now()->subHour());
    }
}
