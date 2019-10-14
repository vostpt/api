<?php

declare(strict_types=1);

namespace VOSTPT\Repositories\Contracts;

use VOSTPT\Models\County;

interface CountyRepository extends Repository, Paginator
{
    /**
     * Find a County by code.
     *
     * @param string $code
     *
     * @return County
     */
    public function findByCode(string $code): ?County;
}
