<?php

declare(strict_types=1);

namespace VOSTPT\Http\Controllers;

use Illuminate\Http\JsonResponse;
use VOSTPT\Filters\Contracts\OccurrenceTypeFilter;
use VOSTPT\Http\Requests\OccurrenceType\Index;
use VOSTPT\Http\Serializers\OccurrenceTypeSerializer;
use VOSTPT\Models\OccurrenceType;
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
        $filter->setSortColumn($request->input('sort', $filter->getSortColumn()))
            ->setSortOrder($request->input('order', $filter->getSortOrder()))
            ->setPageNumber((int) $request->input('page.number', $filter->getPageNumber()))
            ->setPageSize((int) $request->input('page.size', $filter->getPageSize()));

        if ($request->has('search')) {
            $filter->withSearch($request->input('search'), (bool) $request->input('exact', false));
        }

        if ($request->has('species')) {
            $filter->withSpecies(...$request->input('species', []));
        }

        $paginator = $this->createPaginator(OccurrenceType::class, $occurrenceTypeRepository->createQueryBuilder(), $filter);

        return response()->paginator($paginator, new OccurrenceTypeSerializer(), [
            'species',
        ]);
    }
}
