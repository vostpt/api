<?php

declare(strict_types=1);

namespace VOSTPT\Http\Controllers;

use Illuminate\Http\JsonResponse;
use VOSTPT\Filters\Contracts\OccurrenceSpeciesFilter;
use VOSTPT\Http\Requests\OccurrenceSpecies\Index;
use VOSTPT\Http\Serializers\OccurrenceSpeciesSerializer;
use VOSTPT\Models\OccurrenceSpecies;
use VOSTPT\Repositories\Contracts\OccurrenceSpeciesRepository;

class OccurrenceSpeciesController extends Controller
{
    /**
     * Index OccurrenceSpecies.
     *
     * @param Index                       $request
     * @param OccurrenceSpeciesFilter     $filter
     * @param OccurrenceSpeciesRepository $occurrenceSpeciesRepository
     *
     * @throws \OutOfBoundsException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(
        Index $request,
        OccurrenceSpeciesFilter $filter,
        OccurrenceSpeciesRepository $occurrenceSpeciesRepository
    ): JsonResponse {
        $filter->setSortColumn($request->input('sort', $filter->getSortColumn()))
            ->setSortOrder($request->input('order', $filter->getSortOrder()))
            ->setPageNumber((int) $request->input('page.number', $filter->getPageNumber()))
            ->setPageSize((int) $request->input('page.size', $filter->getPageSize()));

        if ($request->has('search')) {
            $filter->withSearch($request->input('search'), (bool) $request->input('exact', false));
        }

        if ($request->has('families')) {
            $filter->withFamilies(...$request->input('families', []));
        }

        return response()->paginator($occurrenceSpeciesRepository->getPaginator($filter), new OccurrenceSpeciesSerializer(), [
            'family',
        ]);
    }
}
