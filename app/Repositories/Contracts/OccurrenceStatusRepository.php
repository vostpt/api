<?php

declare(strict_types=1);

namespace VOSTPT\Repositories\Contracts;

use VOSTPT\Models\OccurrenceStatus;

interface OccurrenceStatusRepository extends Repository
{
    /**
     * Find an OccurrenceStatus by code.
     *
     * @param int $code
     *
     * @return OccurrenceStatus
     */
    public function findByCode(int $code): ?OccurrenceStatus;
}
