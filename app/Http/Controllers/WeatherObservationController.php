<?php

declare(strict_types=1);

namespace VOSTPT\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use VOSTPT\Filters\Contracts\WeatherObservationFilter;
use VOSTPT\Http\Requests\WeatherObservation\Index;
use VOSTPT\Http\Requests\WeatherObservation\View;
use VOSTPT\Http\Serializers\WeatherObservationSerializer;
use VOSTPT\Models\WeatherObservation;
use VOSTPT\Repositories\Contracts\WeatherObservationRepository;

class WeatherObservationController extends Controller
{
    /**
     * Index WeatherObservations.
     *
     * @param Index                        $request
     * @param WeatherObservationFilter     $filter
     * @param WeatherObservationRepository $occurrenceRepository
     *
     * @throws \OutOfBoundsException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(
        Index $request,
        WeatherObservationFilter $filter,
        WeatherObservationRepository $occurrenceRepository
    ): JsonResponse {
        $filter->setSortColumn($request->input('sort', $filter->getSortColumn()))
            ->setSortOrder($request->input('order', $filter->getSortOrder()))
            ->setPageNumber((int) $request->input('page.number', $filter->getPageNumber()))
            ->setPageSize((int) $request->input('page.size', $filter->getPageSize()));

        if ($request->has('search')) {
            $filter->withSearch($request->input('search'), (bool) $request->input('exact', false));
        }

        if ($request->has('districts')) {
            $filter->withDistricts(...$request->input('districts', []));
        }

        if ($request->has('counties')) {
            $filter->withCounties(...$request->input('counties', []));
        }

        if ($request->has('stations')) {
            $filter->withStations(...$request->input('stations', []));
        }

        if ($from = $request->get('timestamp_from')) {
            $filter->withTimestampFrom(Carbon::parse($from));
        }

        if ($to = $request->get('timestamp_to')) {
            $filter->withTimestampTo(Carbon::parse($to));
        }

        return response()->paginator($occurrenceRepository->getPaginator($filter), new WeatherObservationSerializer(), [
            'station',
            'station.county',
        ]);
    }

    /**
     * View a WeatherObservation.
     *
     * @param View               $request
     * @param WeatherObservation $weatherObservation
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function view(View $request, WeatherObservation $weatherObservation): JsonResponse
    {
        return response()->resource($weatherObservation, new WeatherObservationSerializer(), [
            'station',
            'station.county',
        ]);
    }
}
