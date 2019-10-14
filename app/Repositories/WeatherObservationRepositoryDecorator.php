<?php

declare(strict_types=1);

namespace VOSTPT\Repositories;

use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use VOSTPT\Filters\Contracts\Filter;
use VOSTPT\Models\WeatherObservation;
use VOSTPT\Models\WeatherStation;

class WeatherObservationRepositoryDecorator implements Contracts\WeatherObservationRepository
{
    /**
     * WeatherObservation repository implementation.
     *
     * @var Contracts\WeatherObservationRepository
     */
    private $next;

    /**
     * @param Contracts\WeatherObservationRepository $next
     */
    public function __construct(Contracts\WeatherObservationRepository $next)
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
    public function findByStationAndTimestamp(WeatherStation $station, Carbon $timestamp): ?WeatherObservation
    {
        return $this->next->findByStationAndTimestamp($station, $timestamp);
    }

    /**
     * {@inheritdoc}
     */
    public function getPaginator(Filter $filter): LengthAwarePaginator
    {
        return Cache::tags(WeatherObservation::class)->rememberForever($filter->getSignature(), function () use ($filter) {
            return $this->next->getPaginator($filter);
        });
    }
}
