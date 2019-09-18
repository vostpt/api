<?php

declare(strict_types=1);

namespace VOSTPT\Repositories\Contracts;

use VOSTPT\Models\OccurrenceType;

interface OccurrenceTypeRepository extends Repository
{
    /**
     * Find an OccurrenceType by code.
     *
     * @param string $code
     *
     * @return OccurrenceType
     */
    public function findByCode(string $code): ?OccurrenceType;
}
