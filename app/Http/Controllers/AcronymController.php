<?php

declare(strict_types=1);

namespace VOSTPT\Http\Controllers;

use Illuminate\Http\JsonResponse;
use VOSTPT\Filters\Contracts\AcronymFilter;
use VOSTPT\Http\Requests\Acronym\Index;
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
            ->setPageNumber((int) $request->input('page.number', 1))
            ->setPageSize((int) $request->input('page.size', 10));

        if ($search = $request->input('search')) {
            $filter->withSearch($search);
        }

        $paginator = $this->createPaginator(Acronym::class, $acronymRepository->createQueryBuilder(), $filter);

        return response()->paginator($paginator, new AcronymSerializer());
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
        return response()->resource($acronym, new AcronymSerializer(), [
            'district',
        ]);
    }
}
