<?php

declare(strict_types=1);

namespace VOSTPT\Repositories\Contracts;

use VOSTPT\Models\Parish;

interface ParishRepository extends Repository
{
    /**
     * Find a Parish by code.
     *
     * @param string $code
     *
     * @return Parish
     */
    public function findByCode(string $code): ?Parish;
}
