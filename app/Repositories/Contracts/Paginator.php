<?php

declare(strict_types=1);

namespace VOSTPT\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use VOSTPT\Filters\Contracts\Filter;

interface Paginator
{
    /**
     * Get a paginator.
     *
     * @param Filter $filter
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginator(Filter $filter): LengthAwarePaginator;
}
