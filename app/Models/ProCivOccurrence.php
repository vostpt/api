<?php

declare(strict_types=1);

namespace VOSTPT\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ProCivOccurrence extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $table = 'prociv_occurrences';

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'aerial_assets'     => 'int',
        'aerial_operatives' => 'int',
        'ground_assets'     => 'int',
        'ground_operatives' => 'int',
    ];

    /**
     * Associated occurrence.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function occurrence(): MorphOne
    {
        return $this->morphOne(Occurrence::class, 'source');
    }

    /**
     * Associated Type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(ProCivOccurrenceType::class, 'type_id');
    }

    /**
     * Associated Status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(ProCivOccurrenceStatus::class, 'status_id');
    }

    /**
     * Associated logs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs(): HasMany
    {
        return $this->hasMany(ProCivOccurrenceLog::class);
    }

    /**
     * Set the remote id.
     *
     * @param string $remoteId
     *
     * @return void
     */
    public function setRemoteIdAttribute(string $remoteId): void
    {
        $this->attributes['remote_id'] = $remoteId;
    }

    /**
     * Set the ground assets.
     *
     * @param int $groundAssets
     *
     * @return void
     */
    public function setGroundAssetsAttribute(int $groundAssets): void
    {
        $this->attributes['ground_assets'] = $groundAssets;
    }

    /**
     * Set the ground operatives.
     *
     * @param int $groundOperatives
     *
     * @return void
     */
    public function setGroundOperativesAttribute(int $groundOperatives): void
    {
        $this->attributes['ground_operatives'] = $groundOperatives;
    }

    /**
     * Set the aerial assets.
     *
     * @param int $aerialAssets
     *
     * @return void
     */
    public function setAerialAssetsAttribute(int $aerialAssets): void
    {
        $this->attributes['aerial_assets'] = $aerialAssets;
    }

    /**
     * Set the aerial operatives.
     *
     * @param int $aerialOperatives
     *
     * @return void
     */
    public function setAerialOperativesAttribute(int $aerialOperatives): void
    {
        $this->attributes['aerial_operatives'] = $aerialOperatives;
    }
}
