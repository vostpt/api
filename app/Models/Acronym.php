<?php

declare(strict_types=1);

namespace VOSTPT\Models;

use Illuminate\Database\Eloquent\Model;
use LengthException;

class Acronym extends Model
{
    use Concerns\Cacheable;

    /**
     * {@inheritDoc}
     */
    protected $table = 'acronyms';

    /**
     * Set the initials.
     *
     * @param string $initials
     *
     * @throws \LengthException
     *
     * @return void
     */
    public function setInitialsAttribute(string $initials): void
    {
        if (\mb_strlen($initials) > 16) {
            throw new LengthException('The initials cannot have more than 16 characters');
        }

        $this->attributes['initials'] = \mb_strtoupper(\trim($initials));
    }

    /**
     * Set the meaning.
     *
     * @param string $meaning
     *
     * @throws \LengthException
     *
     * @return void
     */
    public function setMeaningAttribute(string $meaning): void
    {
        if (\mb_strlen($meaning) > 255) {
            throw new LengthException('The meaning cannot have more than 255 characters');
        }

        $this->attributes['meaning'] = $meaning;
    }
}
