<?php

declare(strict_types=1);

namespace VOSTPT\Http\Controllers;

use Illuminate\Http\JsonResponse;
use VOSTPT\Filters\Contracts\CountyFilter;
use VOSTPT\Http\Requests\County\Index;
use VOSTPT\Http\Requests\County\View;
use VOSTPT\Http\Serializers\CountySerializer;
use VOSTPT\Models\County;
use VOSTPT\Repositories\Contracts\CountyRepository;

class CountyController extends Controller
{
    /**
     * Index Counties.
     *
     * @param Index            $request
     * @param CountyFilter     $filter
     * @param CountyRepository $districtRepository
     *
     * @throws \InvalidArgumentException
     * @throws \OutOfBoundsException
     * @throws \RuntimeException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Index $request, CountyFilter $filter, CountyRepository $districtRepository): JsonResponse
    {
        $filter->setSortColumn($request->input('sort', $filter->getSortColumn()))
            ->setSortOrder($request->input('order', $filter->getSortOrder()))
            ->setPageNumber((int) $request->input('page.number', 1))
            ->setPageSize((int) $request->input('page.size', 10));

        if ($search = $request->input('search')) {
            $filter->withSearch($search);
        }

        $paginator = $this->createPaginator(County::class, $districtRepository->createQueryBuilder(), $filter);

        return response()->paginator($paginator, new CountySerializer());
    }

    /**
     * View a County.
     *
     * @param View   $request
     * @param County $district
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function view(View $request, County $district): JsonResponse
    {
        return response()->resource($district, new CountySerializer(), [
            'district',
        ]);
    }
}
