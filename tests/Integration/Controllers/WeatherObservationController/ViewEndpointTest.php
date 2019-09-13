<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\WeatherObservationController;

use VOSTPT\Models\WeatherObservation;
use VOSTPT\Tests\Integration\RefreshDatabase;
use VOSTPT\Tests\Integration\TestCase;

class ViewEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itFailsToViewWeatherObservationDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('GET', route('weather::observations::view', [
            'WeatherObservation' => 1,
        ]), [], static::INVALID_CONTENT_TYPE_HEADER);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(415);
        $response->assertJson([
            'errors' => [
                [
                    'status' => 415,
                    'detail' => 'Unsupported media type',
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function itFailsToViewWeatherObservationDueToInvalidAcceptHeader(): void
    {
        $response = $this->json('GET', route('weather::observations::view', [
            'WeatherObservation' => 1,
        ]), [], static::INVALID_ACCEPT_HEADER);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(406);
        $response->assertJson([
            'errors' => [
                [
                    'status' => 406,
                    'detail' => 'Not acceptable',
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function itFailsToViewWeatherObservationDueToRecordNotFound(): void
    {
        $response = $this->json('GET', route('weather::observations::view', [
            'WeatherObservation' => 1,
        ]), [], static::VALID_CONTENT_TYPE_HEADER);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(404);
        $response->assertJson([
            'errors' => [
                [
                    'status' => 404,
                    'detail' => 'WeatherObservation Not Found',
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function itSuccessfullyViewsWeatherObservation(): void
    {
        $weatherObservation = factory(WeatherObservation::class)->create();

        $response = $this->json('GET', route('weather::observations::view', [
            'WeatherObservation' => $weatherObservation->getKey(),
        ]), [], static::VALID_CONTENT_TYPE_HEADER);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'type',
                'id',
                'attributes' => [
                    'temperature',
                    'humidity',
                    'wind_speed',
                    'wind_direction',
                    'precipitation',
                    'atmospheric_pressure',
                    'radiation',
                    'timestamp',
                    'created_at',
                    'updated_at',
                ],
                'relationships' => [
                    'station' => [
                        'data' => [
                            'type',
                            'id',
                        ],
                    ],
                ],
            ],
            'included' => [
                [
                    'type',
                    'id',
                    'attributes' => [
                        'entity',
                        'name',
                        'serial',
                        'created_at',
                        'updated_at',
                    ],
                    'relationships' => [
                        'county' => [
                            'data' => [
                                'type',
                                'id',
                            ],
                        ],
                    ],
                ],
                [
                    'type',
                    'id',
                    'attributes' => [
                        'code',
                        'name',
                        'created_at',
                        'updated_at',
                    ],
                    'links' => [
                        'self',
                    ],
                ],
            ],
        ]);
    }
}
