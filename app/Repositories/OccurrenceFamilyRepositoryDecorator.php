<?php

declare(strict_types=1);

namespace VOSTPT\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use VOSTPT\Filters\Contracts\Filter;
use VOSTPT\Models\OccurrenceFamily;

class OccurrenceFamilyRepositoryDecorator implements Contracts\OccurrenceFamilyRepository
{
    /**
     * OccurrenceFamily repository implementation.
     *
     * @var Contracts\OccurrenceFamilyRepository
     */
    private $next;

    /**
     * @param Contracts\OccurrenceFamilyRepository $next
     */
    public function __construct(Contracts\OccurrenceFamilyRepository $next)
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
        return Cache::tags(OccurrenceFamily::class)->rememberForever($filter->getSignature(), function () use ($filter) {
            return $this->next->getPaginator($filter);
        });
    }
}
