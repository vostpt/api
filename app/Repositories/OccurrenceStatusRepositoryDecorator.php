<?php

declare(strict_types=1);

namespace VOSTPT\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use VOSTPT\Filters\Contracts\Filter;
use VOSTPT\Models\OccurrenceStatus;

class OccurrenceStatusRepositoryDecorator implements Contracts\OccurrenceStatusRepository
{
    /**
     * OccurrenceStatus repository implementation.
     *
     * @var Contracts\OccurrenceStatusRepository
     */
    private $next;

    /**
     * @param Contracts\OccurrenceStatusRepository $next
     */
    public function __construct(Contracts\OccurrenceStatusRepository $next)
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
    public function findByCode(int $code): ?OccurrenceStatus
    {
        return $this->next->findByCode($code);
    }

    /**
     * {@inheritdoc}
     */
    public function getPaginator(Filter $filter): LengthAwarePaginator
    {
        return Cache::tags(OccurrenceStatus::class)->rememberForever($filter->getSignature(), function () use ($filter) {
            return $this->next->getPaginator($filter);
        });
    }
}
