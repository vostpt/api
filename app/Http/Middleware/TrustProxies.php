<?php

declare(strict_types=1);

namespace VOSTPT\Http\Middleware;

use Fideloper\Proxy\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * {@inheritDoc}
     */
    protected $proxies = '*';

    /**
     * {@inheritDoc}
     */
    protected $headers = Request::HEADER_X_FORWARDED_ALL;
}
