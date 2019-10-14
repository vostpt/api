<?php

declare(strict_types=1);

namespace VOSTPT\Http\Controllers;

use Illuminate\Http\JsonResponse;
use VOSTPT\Filters\Contracts\ParishFilter;
use VOSTPT\Http\Requests\Parish\Index;
use VOSTPT\Http\Requests\Parish\View;
use VOSTPT\Http\Serializers\ParishSerializer;
use VOSTPT\Models\Parish;
use VOSTPT\Repositories\Contracts\ParishRepository;

class ParishController extends Controller
{
    /**
     * Index Counties.
     *
     * @param Index            $request
     * @param ParishFilter     $filter
     * @param ParishRepository $parishRepository
     *
     * @throws \OutOfBoundsException
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Index $request, ParishFilter $filter, ParishRepository $parishRepository): JsonResponse
    {
        $filter->setSortColumn($request->input('sort', $filter->getSortColumn()))
            ->setSortOrder($request->input('order', $filter->getSortOrder()))
            ->setPageNumber((int) $request->input('page.number', $filter->getPageNumber()))
            ->setPageSize((int) $request->input('page.size', $filter->getPageSize()));

        if ($request->has('search')) {
            $filter->withSearch($request->input('search'), (bool) $request->input('exact', false));
        }

        if ($request->has('counties')) {
            $filter->withCounties(...$request->input('counties', []));
        }

        return response()->paginator($parishRepository->getPaginator($filter), new ParishSerializer());
    }

    /**
     * View a Parish.
     *
     * @param View   $request
     * @param Parish $parish
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function view(View $request, Parish $parish): JsonResponse
    {
        return response()->resource($parish, new ParishSerializer(), [
            'county',
        ]);
    }
}
