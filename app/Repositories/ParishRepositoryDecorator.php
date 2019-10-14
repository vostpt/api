<?php

declare(strict_types=1);

namespace VOSTPT\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use VOSTPT\Filters\Contracts\Filter;
use VOSTPT\Models\Parish;

class ParishRepositoryDecorator implements Contracts\ParishRepository
{
    /**
     * Parish repository implementation.
     *
     * @var Contracts\ParishRepository
     */
    private $next;

    /**
     * @param Contracts\ParishRepository $next
     */
    public function __construct(Contracts\ParishRepository $next)
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
     * {@inheritDoc}
     */
    public function findByCode(string $code): ?Parish
    {
        return $this->next->findByCode($code);
    }

    /**
     * {@inheritdoc}
     */
    public function getPaginator(Filter $filter): LengthAwarePaginator
    {
        return Cache::tags(Parish::class)->rememberForever($filter->getSignature(), function () use ($filter) {
            return $this->next->getPaginator($filter);
        });
    }
}
