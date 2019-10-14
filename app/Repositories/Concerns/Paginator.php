<?php

declare(strict_types=1);

namespace VOSTPT\Repositories\Concerns;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use VOSTPT\Filters\Contracts\Filter;

trait Paginator
{
    /**
     * {@inheritdoc}
     */
    public function getPaginator(Filter $filter): LengthAwarePaginator
    {
        $builder = $this->createQueryBuilder();

        $filter->apply($builder);

        return $builder->paginate(
            $filter->getPageSize(),
            $filter->getColumns(),
            'page[number]',
            $filter->getPageNumber()
        )->appends($filter->getUrlParameters());
    }
}
