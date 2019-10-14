<?php

declare(strict_types=1);

namespace VOSTPT\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use VOSTPT\Filters\Contracts\Filter;
use VOSTPT\Models\Acronym;

class AcronymRepositoryDecorator implements Contracts\AcronymRepository
{
    /**
     * Acronym repository implementation.
     *
     * @var Contracts\AcronymRepository
     */
    private $next;

    /**
     * @param Contracts\AcronymRepository $next
     */
    public function __construct(Contracts\AcronymRepository $next)
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
        return Cache::tags(Acronym::class)->rememberForever($filter->getSignature(), function () use ($filter) {
            return $this->next->getPaginator($filter);
        });
    }
}
