<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Commands\Ipma;

use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Psr\Log\Test\TestLogger;
use VOSTPT\Models\County;
use VOSTPT\Tests\Integration\HttpClientMocker;
use VOSTPT\Tests\Integration\TestCase;

class WarningFetchCommandTest extends TestCase
{
    use HttpClientMocker;
    use RefreshDatabase;

    /**
     * @test
     */
    public function itSuccessfullyFetchesSurfaceObservations(): void
    {
        $response = $this->createHttpResponse('tests/data/Ipma/Warnings.json');

        $this->app->instance(Client::class, $this->createHttpClient($response));

        $this->app->instance(LoggerInterface::class, new NullLogger());

        $this->assertTrue(Cache::missing('ipma_warnings'));

        factory(County::class)->create([
            'code' => '030300',
        ]);

        $this->artisan('ipma:fetch:warnings');

        $this->assertTrue(Cache::has('ipma_warnings'));
    }

    /**
     * @test
     */
    public function itFailsToFetchSurfaceObservations(): void
    {
        $response = $this->createHttpResponse(null, 404);

        $this->app->instance(Client::class, $this->createHttpClient($response));

        $this->app->instance(LoggerInterface::class, new TestLogger());

        $this->artisan('ipma:fetch:warnings');

        $logger = $this->app[LoggerInterface::class];

        $this->assertTrue($logger->hasRecordThatContains('Client error: `GET https://api.ipma.pt/json/warnings_www.json` resulted in a `404 Not Found`', 'error'));
    }
}
