<?php

declare(strict_types=1);

namespace VOSTPT\Http\Controllers;

use Illuminate\Http\JsonResponse;
use VOSTPT\Filters\Contracts\DistrictFilter;
use VOSTPT\Http\Requests\District\Index;
use VOSTPT\Http\Requests\District\View;
use VOSTPT\Http\Serializers\DistrictSerializer;
use VOSTPT\Models\District;
use VOSTPT\Repositories\Contracts\DistrictRepository;

class DistrictController extends Controller
{
    /**
     * Index Districts.
     *
     * @param Index              $request
     * @param DistrictFilter     $filter
     * @param DistrictRepository $districtRepository
     *
     * @throws \InvalidArgumentException
     * @throws \OutOfBoundsException
     * @throws \RuntimeException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Index $request, DistrictFilter $filter, DistrictRepository $districtRepository): JsonResponse
    {
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

        return response()->paginator($districtRepository->getPaginator($filter), new DistrictSerializer());
    }

    /**
     * View a District.
     *
     * @param View     $request
     * @param District $district
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function view(View $request, District $district): JsonResponse
    {
        return response()->resource($district, new DistrictSerializer());
    }
}
