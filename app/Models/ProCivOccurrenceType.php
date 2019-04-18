<?php

declare(strict_types=1);

namespace VOSTPT\Models;

use Illuminate\Database\Eloquent\Model;
use LengthException;

class ProCivOccurrenceType extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $table = 'prociv_occurrence_types';

    /**
     * Set the code.
     *
     * @param int $code
     *
     * @return void
     */
    public function setCodeAttribute(int $code): void
    {
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
