<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Commands\Ipma;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use VOSTPT\Tests\Integration\HttpClientMocker;
use VOSTPT\Tests\Integration\TestCase;

class ResponseCacheBustCommandTest extends TestCase
{
    use HttpClientMocker;
    use RefreshDatabase;

    /**
     * @test
     */
    public function itDoesNotBustsResponseCache(): void
    {
        $this->assertFalse(Cache::has('tags_for_cache_busting'));

        $this->artisan('api:bust:response-cache');

        $this->assertFalse(Cache::has('tags_for_cache_busting'));
    }

    /**
     * @test
     */
    public function itSuccessfullyBustsResponseCache(): void
    {
        $this->assertFalse(Cache::has('tags_for_cache_busting'));

        add_cache_bust_tag('foo');

        $this->assertTrue(Cache::has('tags_for_cache_busting'));

        $this->artisan('api:bust:response-cache');

        $this->assertFalse(Cache::has('tags_for_cache_busting'));
    }
}
