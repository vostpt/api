<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Commands\Ipma;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Psr\Log\Test\TestLogger;
use VOSTPT\Models\WeatherObservation;
use VOSTPT\Models\WeatherStation;
use VOSTPT\Tests\Integration\HttpClientMocker;
use VOSTPT\Tests\Integration\TestCase;

class SurfaceObservationFetchCommandTest extends TestCase
{
    use HttpClientMocker;
    use RefreshDatabase;

    /**
     * @test
     */
    public function itSuccessfullyFetchesSurfaceObservations(): void
    {
        $response = $this->createHttpResponse('tests/data/Ipma/SurfaceObservations.json');

        $this->app->instance(ClientInterface::class, $this->createHttpClient($response));

        $this->app->instance(LoggerInterface::class, new NullLogger());

        $station = factory(WeatherStation::class)->create([
            'serial' => '123',
            'entity' => 'IPMA',
        ]);

        factory(WeatherObservation::class)->create([
            'station_id' => $station->getKey(),
            'timestamp'  => '1975-07-25 16:25:00',
        ]);

        $this->assertDatabaseMissing('weather_observations', [
            'station_id' => 1,
            'timestamp'  => '1981-12-16 03:33:00',
        ]);

        $this->artisan('ipma:fetch:surface-observations');

        $this->assertDatabaseHas('weather_observations', [
            'station_id' => 1,
            'timestamp'  => '1981-12-16 03:33:00',
        ]);
    }

    /**
     * @test
     */
    public function itFailsToFetchSurfaceObservations(): void
    {
        $response = $this->createHttpResponse(null, 404);

        $this->app->instance(ClientInterface::class, $this->createHttpClient($response));

        $this->app->instance(LoggerInterface::class, new TestLogger());

        $this->artisan('ipma:fetch:surface-observations');

        $logger = $this->app[LoggerInterface::class];

        $this->assertTrue($logger->hasRecordThatContains('https://api.ipma.pt/open-data/observation/meteorology/stations/observations.json (Not Found)', 'error'));
    }
}
