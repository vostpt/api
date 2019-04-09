<?php

declare(strict_types=1);

namespace VOSTPT\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class Repository implements Contracts\Repository
{
    /**
     * {@inheritDoc}
     */
    public function findById(int $id): ?Model
    {
        return $this->createQueryBuilder()
            ->where('id', $id)
            ->first();
    }
}
