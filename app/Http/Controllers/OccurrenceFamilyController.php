<?php

declare(strict_types=1);

namespace VOSTPT\Http\Controllers;

use Illuminate\Http\JsonResponse;
use VOSTPT\Filters\Contracts\OccurrenceFamilyFilter;
use VOSTPT\Http\Requests\OccurrenceType\Index;
use VOSTPT\Http\Serializers\OccurrenceFamilySerializer;
use VOSTPT\Models\OccurrenceFamily;
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
        $filter->setSortColumn($request->input('sort', $filter->getSortColumn()))
            ->setSortOrder($request->input('order', $filter->getSortOrder()))
            ->setPageNumber((int) $request->input('page.number', $filter->getPageNumber()))
            ->setPageSize((int) $request->input('page.size', $filter->getPageSize()));

        if ($request->has('search')) {
            $filter->withSearch($request->input('search'), (bool) $request->input('exact', false));
        }

        $paginator = $this->createPaginator(OccurrenceFamily::class, $occurrenceFamilyRepository->createQueryBuilder(), $filter);

        return response()->paginator($paginator, new OccurrenceFamilySerializer());
    }
}
