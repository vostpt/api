<?php

declare(strict_types=1);

namespace VOSTPT\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use VOSTPT\Filters\Contracts\Filter;
use VOSTPT\Models\OccurrenceType;

class OccurrenceTypeRepositoryDecorator implements Contracts\OccurrenceTypeRepository
{
    /**
     * OccurrenceType repository implementation.
     *
     * @var Contracts\OccurrenceTypeRepository
     */
    private $next;

    /**
     * @param Contracts\OccurrenceTypeRepository $next
     */
    public function __construct(Contracts\OccurrenceTypeRepository $next)
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
    public function findByCode(string $code): ?OccurrenceType
    {
        return $this->next->findByCode($code);
    }

    /**
     * {@inheritdoc}
     */
    public function getPaginator(Filter $filter): LengthAwarePaginator
    {
        return Cache::tags(OccurrenceType::class)->rememberForever($filter->getSignature(), function () use ($filter) {
            return $this->next->getPaginator($filter);
        });
    }
}
