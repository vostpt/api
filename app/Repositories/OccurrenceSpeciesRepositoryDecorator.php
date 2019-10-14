<?php

declare(strict_types=1);

namespace VOSTPT\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use VOSTPT\Filters\Contracts\Filter;
use VOSTPT\Models\OccurrenceSpecies;

class OccurrenceSpeciesRepositoryDecorator implements Contracts\OccurrenceSpeciesRepository
{
    /**
     * OccurrenceSpecies repository implementation.
     *
     * @var Contracts\OccurrenceSpeciesRepository
     */
    private $next;

    /**
     * @param Contracts\OccurrenceSpeciesRepository $next
     */
    public function __construct(Contracts\OccurrenceSpeciesRepository $next)
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
        return Cache::tags(OccurrenceSpecies::class)->rememberForever($filter->getSignature(), function () use ($filter) {
            return $this->next->getPaginator($filter);
        });
    }
}
