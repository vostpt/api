<?php

declare(strict_types=1);

namespace VOSTPT\Http\Controllers;

use Illuminate\Http\JsonResponse;
use VOSTPT\Filters\Contracts\OccurrenceFilter;
use VOSTPT\Http\Requests\Occurrence\Index;
use VOSTPT\Http\Requests\Occurrence\View;
use VOSTPT\Http\Serializers\OccurrenceSerializer;
use VOSTPT\Models\Occurrence;
use VOSTPT\Repositories\Contracts\OccurrenceRepository;

class OccurrenceController extends Controller
{
    /**
     * Index Occurrences.
     *
     * @param Index                $request
     * @param OccurrenceFilter     $filter
     * @param OccurrenceRepository $occurrenceRepository
     *
     * @throws \OutOfBoundsException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Index $request, OccurrenceFilter $filter, OccurrenceRepository $occurrenceRepository): JsonResponse
    {
        $filter->setSortColumn($request->input('sort', $filter->getSortColumn()))
            ->setSortOrder($request->input('order', $filter->getSortOrder()))
            ->setPageNumber((int) $request->input('page.number', 1))
            ->setPageSize((int) $request->input('page.size', 50));

        if ($request->has('search')) {
            $filter->withSearch($request->input('search'));
        }

        if ($request->has('events')) {
            $filter->withEvents(...$request->input('events', []));
        }

        if ($request->has('parishes')) {
            $filter->withParishes(...$request->input('parishes', []));
        }

        $paginator = $this->createPaginator(Occurrence::class, $occurrenceRepository->createQueryBuilder(), $filter);

        return response()->paginator($paginator, new OccurrenceSerializer());
    }

    /**
     * View an Occurrence.
     *
     * @param View       $request
     * @param Occurrence $occurrence
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function view(View $request, Occurrence $occurrence): JsonResponse
    {
        return response()->resource($occurrence, new OccurrenceSerializer(), [
            'event',
            'parish',
            'source',
        ]);
    }
}
