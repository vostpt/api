<?php

declare(strict_types=1);

namespace VOSTPT\Repositories;

use Illuminate\Database\Eloquent\Builder;
use VOSTPT\Models\County;

class CountyRepository extends Repository implements Contracts\CountyRepository
{
    use Concerns\Paginator;

    /**
     * {@inheritDoc}
     */
    public function createQueryBuilder(): Builder
    {
        return County::query();
    }

    /**
     * {@inheritDoc}
     */
    public function findByCode(string $code): ?County
    {
        return $this->createQueryBuilder()
            ->where('code', $code)
            ->first();
    }
}
