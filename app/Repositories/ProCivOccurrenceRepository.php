<?php

declare(strict_types=1);

namespace VOSTPT\Repositories;

use Illuminate\Database\Eloquent\Builder;
use VOSTPT\Models\ProCivOccurrence;

class ProCivOccurrenceRepository extends Repository implements Contracts\ProCivOccurrenceRepository
{
    /**
     * {@inheritDoc}
     */
    public function createQueryBuilder(): Builder
    {
        return ProCivOccurrence::query();
    }

    /**
     * {@inheritDoc}
     */
    public function findByRemoteId(string $id): ?ProCivOccurrence
    {
        return $this->createQueryBuilder()
            ->where('remote_id', $id)
            ->first();
    }
}
