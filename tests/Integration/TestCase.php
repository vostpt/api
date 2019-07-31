<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration;

abstract class TestCase extends \Illuminate\Foundation\Testing\TestCase
{
    use CreatesApplication;

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
