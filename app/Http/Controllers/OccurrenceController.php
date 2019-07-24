<?php

declare(strict_types=1);

namespace VOSTPT\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use VOSTPT\Filters\Contracts\OccurrenceFilter;
use VOSTPT\Http\Requests\Occurrence\Index;
use VOSTPT\Http\Requests\Occurrence\Update;
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
            ->setPageNumber((int) $request->input('page.number', $filter->getPageNumber()))
            ->setPageSize((int) $request->input('page.size', $filter->getPageSize()));

        if ($request->has('search')) {
            $filter->withSearch($request->input('search'), (bool) $request->input('exact', false));
        }

        if ($request->has('events')) {
            $filter->withEvents(...$request->input('events', []));
        }

        if ($request->has('types')) {
            $filter->withTypes(...$request->input('types', []));
        }

        if ($request->has('statuses')) {
            $filter->withStatuses(...$request->input('statuses', []));
        }

        if ($request->has('districts')) {
            $filter->withDistricts(...$request->input('districts', []));
        }

        if ($request->has('counties')) {
            $filter->withCounties(...$request->input('counties', []));
        }

        if ($request->has('parishes')) {
            $filter->withParishes(...$request->input('parishes', []));
        }

        if ($startedAt = $request->get('started_at')) {
            $filter->withStartedAt(Carbon::parse($startedAt));
        }

        if ($endedAt = $request->get('ended_at')) {
            $filter->withEndedAt(Carbon::parse($endedAt));
        }

        $paginator = $this->createPaginator(Occurrence::class, $occurrenceRepository->createQueryBuilder(), $filter);

        return response()->paginator($paginator, new OccurrenceSerializer(), [
            'type',
            'status',
            'parish',
        ]);
    }

    /**
     * Update an Occurrence.
     *
     * @param Update     $request
     * @param Occurrence $occurrence
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Update $request, Occurrence $occurrence): JsonResponse
    {
        if ($request->has('event')) {
            $occurrence->event()->associate($request->input('event'));
        }

        $occurrence->save();

        return response()->resource($occurrence, new OccurrenceSerializer(), [
            'event',
            'type',
            'status',
            'parish',
            'source',
        ]);
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
            'type',
            'status',
            'parish',
            'source',
        ]);
    }
}
