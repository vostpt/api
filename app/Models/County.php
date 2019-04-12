<?php

declare(strict_types=1);

namespace VOSTPT\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LengthException;

class County extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $table = 'counties';

    /**
     * Associated District.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Set the code.
     *
     * @param string $code
     *
     * @throws \LengthException
     *
     * @return void
     */
    public function setCodeAttribute(string $code): void
    {
        if (\mb_strlen($code) !== 6) {
            throw new LengthException('The code must have 6 characters');
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
            throw new LengthException('The name cannot have more than 255 characters');
        }

        $this->attributes['name'] = $name;
    }
}
