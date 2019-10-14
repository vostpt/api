<?php

declare(strict_types=1);

namespace VOSTPT\Http\Controllers;

use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use VOSTPT\Http\Requests\Warning\Index;
use VOSTPT\Http\Serializers\WarningSerializer;

class IpmaWarningController extends Controller
{
    /**
     * Index IPMA warnings.
     *
     * @param Index $request
     * @param Cache $cache
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Index $request, Cache $cache): JsonResponse
    {
        return response()->collection(new Collection($cache->get('ipma_warnings', [])), new WarningSerializer(), [
            'county',
            'county.district',
        ]);
    }
}
