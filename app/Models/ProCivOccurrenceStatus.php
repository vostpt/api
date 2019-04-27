<?php

declare(strict_types=1);

namespace VOSTPT\Models;

use DomainException;
use Illuminate\Database\Eloquent\Model;
use LengthException;

class ProCivOccurrenceStatus extends Model
{
    public const FALSE_ALERT          = 1;
    public const SURVEILLANCE         = 2;
    public const DISPATCH             = 3;
    public const FIRST_ALERT_DISPATCH = 4;
    public const ONGOING              = 5;
    public const ARRIVAL_AT_TO        = 6;
    public const RESOLVING            = 7;
    public const CONCLUSION           = 8;
    public const CLOSED               = 9;
    public const CLOSED_BY_VOST       = 255;

    /**
     * {@inheritDoc}
     */
    protected $table = 'prociv_occurrence_statuses';

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
        if ($code < 1 || $code > 255) {
            throw new DomainException('The code must be an integer between 1 and 255');
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
