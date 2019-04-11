<?php

declare(strict_types=1);

namespace VOSTPT\Repositories;

use Illuminate\Database\Eloquent\Builder;
use VOSTPT\Models\County;

class CountyRepository extends Repository implements Contracts\CountyRepository
{
    /**
     * {@inheritDoc}
     */
    public function createQueryBuilder(): Builder
    {
        return County::query();
    }
}
