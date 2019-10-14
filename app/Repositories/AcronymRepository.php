<?php

declare(strict_types=1);

namespace VOSTPT\Repositories;

use Illuminate\Database\Eloquent\Builder;
use VOSTPT\Models\Acronym;

class AcronymRepository extends Repository implements Contracts\AcronymRepository
{
    use Concerns\Paginator;

    /**
     * {@inheritDoc}
     */
    public function createQueryBuilder(): Builder
    {
        return Acronym::query();
    }
}
