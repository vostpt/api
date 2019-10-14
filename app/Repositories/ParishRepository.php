<?php

declare(strict_types=1);

namespace VOSTPT\Repositories;

use Illuminate\Database\Eloquent\Builder;
use VOSTPT\Models\Parish;

class ParishRepository extends Repository implements Contracts\ParishRepository
{
    use Concerns\Paginator;

    /**
     * {@inheritDoc}
     */
    public function createQueryBuilder(): Builder
    {
        return Parish::query();
    }

    /**
     * {@inheritDoc}
     */
    public function findByCode(string $code): ?Parish
    {
        return $this->createQueryBuilder()
            ->where('code', $code)
            ->first();
    }
}
