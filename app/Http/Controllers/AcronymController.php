<?php

declare(strict_types=1);

namespace VOSTPT\Http\Controllers;

use Illuminate\Http\JsonResponse;
use VOSTPT\Filters\Contracts\AcronymFilter;
use VOSTPT\Http\Requests\Acronym\Create;
use VOSTPT\Http\Requests\Acronym\Delete;
use VOSTPT\Http\Requests\Acronym\Index;
use VOSTPT\Http\Requests\Acronym\Update;
use VOSTPT\Http\Requests\Acronym\View;
use VOSTPT\Http\Serializers\AcronymSerializer;
use VOSTPT\Models\Acronym;
use VOSTPT\Repositories\Contracts\AcronymRepository;

class AcronymController extends Controller
{
    /**
     * Index Acronyms.
     *
     * @param Index             $request
     * @param AcronymFilter     $filter
     * @param AcronymRepository $acronymRepository
     *
     * @throws \InvalidArgumentException
     * @throws \OutOfBoundsException
     * @throws \RuntimeException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Index $request, AcronymFilter $filter, AcronymRepository $acronymRepository): JsonResponse
    {
        $filter->setSortColumn($request->input('sort', $filter->getSortColumn()))
            ->setSortOrder($request->input('order', $filter->getSortOrder()))
            ->setPageNumber((int) $request->input('page.number', $filter->getPageNumber()))
            ->setPageSize((int) $request->input('page.size', $filter->getPageSize()));

        if ($request->has('search')) {
            $filter->withSearch($request->input('search'), (bool) $request->input('exact', false));
        }

        return response()->paginator($acronymRepository->getPaginator($filter), new AcronymSerializer());
    }

    /**
     * Create an Acronym.
     *
     * @param Create $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Create $request): JsonResponse
    {
        $acronym = new Acronym();

        $acronym->initials = $request->input('initials');
        $acronym->meaning  = $request->input('meaning');

        $acronym->save();

        return response()->resource($acronym, new AcronymSerializer(), [], 201);
    }

    /**
     * View an Acronym.
     *
     * @param View    $request
     * @param Acronym $acronym
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function view(View $request, Acronym $acronym): JsonResponse
    {
        return response()->resource($acronym, new AcronymSerializer());
    }

    /**
     * Update an Acronym.
     *
     * @param Update  $request
     * @param Acronym $acronym
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Update $request, Acronym $acronym): JsonResponse
    {
        if ($request->has('initials')) {
            $acronym->initials = $request->input('initials');
        }

        if ($request->has('meaning')) {
            $acronym->meaning = $request->input('meaning');
        }

        $acronym->save();

        return response()->resource($acronym, new AcronymSerializer());
    }

    /**
     * Delete an Acronym.
     *
     * @param Delete  $request
     * @param Acronym $acronym
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function delete(Delete $request, Acronym $acronym): JsonResponse
    {
        $acronym->delete();

        return response()->resource($acronym, new AcronymSerializer());
    }
}
