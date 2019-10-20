<?php

declare(strict_types=1);

namespace VOSTPT\Http\Controllers;

use Illuminate\Http\JsonResponse;
use VOSTPT\Filters\Contracts\OccurrenceStatusFilter;
use VOSTPT\Http\Requests\OccurrenceStatus\Index;
use VOSTPT\Http\Serializers\OccurrenceStatusSerializer;
use VOSTPT\Repositories\Contracts\OccurrenceStatusRepository;

class OccurrenceStatusController extends Controller
{
    /**
     * Index OccurrenceStatuses.
     *
     * @param Index                      $request
     * @param OccurrenceStatusFilter     $filter
     * @param OccurrenceStatusRepository $occurrenceStatusRepository
     *
     * @throws \OutOfBoundsException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(
        Index $request,
        OccurrenceStatusFilter $filter,
        OccurrenceStatusRepository $occurrenceStatusRepository
    ): JsonResponse {
        $filter->setSortColumn($request->input('sort', $filter->getSortColumn()))
            ->setSortOrder($request->input('order', $filter->getSortOrder()))
            ->setPageNumber((int) $request->input('page.number', $filter->getPageNumber()))
            ->setPageSize((int) $request->input('page.size', $filter->getPageSize()));

        if ($request->has('ids')) {
            $filter->withIds(...$request->input('ids', []));
        }

        if ($request->has('search')) {
            $filter->withSearch($request->input('search'), (bool) $request->input('exact', false));
        }

        return response()->paginator($occurrenceStatusRepository->getPaginator($filter), new OccurrenceStatusSerializer());
    }
}
