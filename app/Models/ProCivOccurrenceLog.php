<?php

declare(strict_types=1);

namespace VOSTPT\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProCivOccurrenceLog extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $table = 'prociv_occurrence_logs';

    /**
     * Associated ProCivOccurrence.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function occurrence(): BelongsTo
    {
        return $this->belongsTo(ProCivOccurrence::class, 'occurrence_id');
    }
}
