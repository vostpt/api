<?php

declare(strict_types=1);

namespace VOSTPT\Models;

use DomainException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LengthException;

class OccurrenceType extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $table = 'occurrence_types';

    /**
     * Associated Species.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function species(): BelongsTo
    {
        return $this->belongsTo(OccurrenceSpecies::class, 'species_id');
    }

    /**
     * Set the code.
     *
     * @param int $code
     *
     * @throws \DomainException
     *
     * @return void
     */
    public function setCodeAttribute(int $code): void
    {
        if ($code < 1000 || $code > 9999) {
            throw new DomainException('The code must be an integer between 1000 and 9999');
        }

        $this->attributes['code'] = $code;
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
}
