<?php

declare(strict_types=1);

namespace VOSTPT\Models;

use Illuminate\Database\Eloquent\Model;

class IpmaWarning extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $table = 'ipma_warnings';

    /**
     * {@inheritDoc}
     */
    protected $dates = [
        'started_at',
        'ended_at',
    ];
}
