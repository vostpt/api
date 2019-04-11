<?php

declare(strict_types=1);

namespace VOSTPT\Repositories\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface Repository
{
    /**
     * Create a new query builder instance.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function createQueryBuilder(): Builder;

    /**
     * Find a Model by id.
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findById(int $id): ?Model;
}
