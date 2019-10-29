<?php

declare(strict_types=1);

namespace VOSTPT\Http\Controllers;

use Illuminate\Http\JsonResponse;
use VOSTPT\Filters\Contracts\OccurrenceTypeFilter;
use VOSTPT\Http\Requests\OccurrenceType\Index;
use VOSTPT\Http\Serializers\OccurrenceTypeSerializer;
use VOSTPT\Repositories\Contracts\OccurrenceTypeRepository;

class OccurrenceTypeController extends Controller
{
    /**
     * Index OccurrenceTypes.
     *
     * @param Index                    $request
     * @param OccurrenceTypeFilter     $filter
     * @param OccurrenceTypeRepository $occurrenceTypeRepository
     *
     * @throws \OutOfBoundsException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(
        Index $request,
        OccurrenceTypeFilter $filter,
        OccurrenceTypeRepository $occurrenceTypeRepository
    ): JsonResponse {
        $filter->setSortColumn($request->input('sort', $filter->getSortColumn()));
        $filter->setSortOrder($request->input('order', $filter->getSortOrder()));
        $filter->setPageNumber((int) $request->input('page.number', $filter->getPageNumber()));
        $filter->setPageSize((int) $request->input('page.size', $filter->getPageSize()));

        if ($request->has('ids')) {
            $filter->withIds(...$request->input('ids', []));
        }

        if ($request->has('search')) {
            $filter->withSearch($request->input('search'), (bool) $request->input('exact', false));
        }

        if ($request->has('species')) {
            $filter->withSpecies(...$request->input('species', []));
        }

        return response()->paginator($occurrenceTypeRepository->getPaginator($filter), new OccurrenceTypeSerializer(), [
            'species',
        ]);
    }
}
