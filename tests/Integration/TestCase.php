<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration;

abstract class TestCase extends \Illuminate\Foundation\Testing\TestCase
{
    use CreatesApplication;

    protected const INVALID_ACCEPT_HEADER = [
        'Accept' => 'application/vnd.api+json;charset=utf-8',
    ];

    protected const INVALID_CONTENT_TYPE_HEADER = [
        'Content-Type' => 'application/vnd.api+json;charset=utf-8',
    ];

    protected const VALID_CONTENT_TYPE_HEADER = [
        'Content-Type' => 'application/vnd.api+json',
    ];

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Make sure tests don't get throttled when hitting the endpoints
        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);
    }
}
