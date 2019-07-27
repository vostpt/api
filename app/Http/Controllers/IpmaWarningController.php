<?php

declare(strict_types=1);

namespace VOSTPT\Http\Controllers;

use Illuminate\Http\JsonResponse;
use VOSTPT\Http\Serializers\IpmaWarningSerializer;
use VOSTPT\Models\IpmaWarning;

class IpmaWarningController extends Controller
{
    /**
     * Index Ipma warnings.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $data = IpmaWarning::query()->where('is_active', 1)->get();
        return response()->collection($data, new IpmaWarningSerializer());
    }
}
