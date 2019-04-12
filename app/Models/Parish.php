<?php

declare(strict_types=1);

namespace VOSTPT\Models;

use DomainException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LengthException;

class Parish extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $table = 'parishes';

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
     * Set the code.
     *
     * @param string $code
     *
     * @return void
     *
     * @throws \DomainException
     */
    public function setCodeAttribute(string $code): void
    {
        if (! \is_numeric($code)) {
            throw new DomainException('The code must be numeric');
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
