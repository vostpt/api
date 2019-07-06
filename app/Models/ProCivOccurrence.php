<?php

declare(strict_types=1);

namespace VOSTPT\Models;

use Illuminate\Database\Eloquent\Model;
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
        'aerial_assets_involved'     => 'int',
        'aerial_operatives_involved' => 'int',
        'ground_assets_involved'     => 'int',
        'ground_operatives_involved' => 'int',
    ];

    /**
     * Parent occurrence.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function parent(): MorphOne
    {
        return $this->morphOne(Occurrence::class, 'source');
    }

    /**
     * Associated logs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs(): HasMany
    {
        return $this->hasMany(ProCivOccurrenceLog::class, 'occurrence_id');
    }
}
