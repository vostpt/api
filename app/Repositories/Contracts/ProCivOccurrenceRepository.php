<?php

declare(strict_types=1);

namespace VOSTPT\Repositories\Contracts;

use VOSTPT\Models\ProCivOccurrence;

interface ProCivOccurrenceRepository extends Repository
{
    /**
     * Find a ProCivOccurrence by remote id.
     *
     * @param string $id
     *
     * @return ProCivOccurrence
     */
    public function findByRemoteId(string $id): ?ProCivOccurrence;
}
