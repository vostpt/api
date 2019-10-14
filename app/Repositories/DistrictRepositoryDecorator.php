<?php

declare(strict_types=1);

namespace VOSTPT\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use VOSTPT\Filters\Contracts\Filter;
use VOSTPT\Models\District;

class DistrictRepositoryDecorator implements Contracts\DistrictRepository
{
    /**
     * District repository implementation.
     *
     * @var Contracts\DistrictRepository
     */
    private $next;

    /**
     * @param Contracts\DistrictRepository $next
     */
    public function __construct(Contracts\DistrictRepository $next)
    {
        $this->next = $next;
    }

    /**
     * {@inheritdoc}
     */
    public function findById(int $id): ?Model
    {
        return $this->next->findById($id);
    }

    /**
     * {@inheritDoc}
     */
    public function createQueryBuilder(): Builder
    {
        return $this->next->createQueryBuilder();
    }

    /**
     * {@inheritdoc}
     */
    public function getPaginator(Filter $filter): LengthAwarePaginator
    {
        return Cache::tags(District::class)->rememberForever($filter->getSignature(), function () use ($filter) {
            return $this->next->getPaginator($filter);
        });
    }
}
