<?php

declare(strict_types=1);

namespace VOSTPT\Http\Controllers;

use Illuminate\Http\JsonResponse;
use VOSTPT\Filters\Contracts\OccurrenceFamilyFilter;
use VOSTPT\Http\Requests\OccurrenceFamily\Index;
use VOSTPT\Http\Serializers\OccurrenceFamilySerializer;
use VOSTPT\Repositories\Contracts\OccurrenceFamilyRepository;

class OccurrenceFamilyController extends Controller
{
    /**
     * Index OccurrenceFamilies.
     *
     * @param Index                      $request
     * @param OccurrenceFamilyFilter     $filter
     * @param OccurrenceFamilyRepository $occurrenceFamilyRepository
     *
     * @throws \OutOfBoundsException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(
        Index $request,
        OccurrenceFamilyFilter $filter,
        OccurrenceFamilyRepository $occurrenceFamilyRepository
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

        return response()->paginator($occurrenceFamilyRepository->getPaginator($filter), new OccurrenceFamilySerializer());
    }
}
