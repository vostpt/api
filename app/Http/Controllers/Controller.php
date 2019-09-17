<?php

declare(strict_types=1);

namespace VOSTPT\Http\Controllers;

use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use VOSTPT\Filters\Contracts\Filter;

abstract class Controller
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * Cache implementation.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * Controller constructor.
     *
     * @param \Illuminate\Contracts\Cache\Repository $cache
     */
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Create a LengthAwarePaginator.
     *
     * @param string                                $tag
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \VOSTPT\Filters\Contracts\Filter      $filter
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    protected function createPaginator(string $tag, Builder $builder, Filter $filter): LengthAwarePaginator
    {
        // Cache the paginator
        return $this->cache->tags($tag)->rememberForever($filter->getSignature(), function () use ($filter, $builder) {
            $filter->apply($builder);

            return $builder->paginate(
                $filter->getPageSize(),
                $filter->getColumns(),
                'page[number]',
                $filter->getPageNumber()
            )->appends([
                'page[size]' => $filter->getPageSize(),
                'sort'       => $filter->getSortColumn(),
                'order'      => $filter->getSortOrder(),
            ]);
        });
    }
}
