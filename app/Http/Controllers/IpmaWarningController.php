<?php

declare(strict_types=1);

namespace VOSTPT\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Tobscure\JsonApi\Collection;
use Tobscure\JsonApi\Document;
use VOSTPT\Http\Serializers\IpmaWarningSerializer;

class IpmaWarningController extends Controller
{
    /**
     * Index Ipma warnings.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $ipmaWarnings = [];

        if (Cache::has('ipma_warnings')) {
            $ipmaWarnings = Cache::get('ipma_warnings');
        }

        $ipmaWarningsCollection = (new Collection($ipmaWarnings, new IpmaWarningSerializer()));
        $data                   = new Document($ipmaWarningsCollection);

        return response()->json($data);
    }
}
