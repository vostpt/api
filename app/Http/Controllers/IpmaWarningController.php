<?php

declare(strict_types=1);

namespace VOSTPT\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Tobscure\JsonApi\Collection;
use Tobscure\JsonApi\Document;
use VOSTPT\Http\Serializers\IpmaWarningSerializer;

class IpmaWarningController extends Controller
{
    /**
     * Index Ipma warnings.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function index(): JsonResponse
    {
        $ipmaWarnings = [];

        if ($this->cache->has('ipma_warnings')) {
            $ipmaWarnings = $this->cache->get('ipma_warnings');
        }

        $ipmaWarningsCollection = (new Collection($ipmaWarnings, new IpmaWarningSerializer()))
            ->with(['county', 'district']);
        $data = new Document($ipmaWarningsCollection);

        return response()->json($data);
    }
}
