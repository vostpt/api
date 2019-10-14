<?php

declare(strict_types=1);

namespace VOSTPT\Repositories;

use Illuminate\Database\Eloquent\Builder;
use VOSTPT\Models\District;

class DistrictRepository extends Repository implements Contracts\DistrictRepository
{
    use Concerns\Paginator;

    /**
     * {@inheritDoc}
     */
    public function createQueryBuilder(): Builder
    {
        return District::query();
    }
}
