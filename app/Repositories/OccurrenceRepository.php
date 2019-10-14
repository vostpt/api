<?php

declare(strict_types=1);

namespace VOSTPT\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use VOSTPT\Models\Occurrence;

class OccurrenceRepository extends Repository implements Contracts\OccurrenceRepository
{
    use Concerns\Paginator;

    /**
     * {@inheritDoc}
     */
    public function createQueryBuilder(): Builder
    {
        return Occurrence::query();
    }

    /**
     * {@inheritDoc}
     */
    public function getStalled(): Collection
    {
        return $this->createQueryBuilder()
            ->stalled()
            ->get();
    }
}
