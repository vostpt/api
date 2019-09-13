<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\WeatherObservationController;

use Carbon\Carbon;
use VOSTPT\Models\County;
use VOSTPT\Models\District;
use VOSTPT\Models\WeatherObservation;
use VOSTPT\Models\WeatherStation;
use VOSTPT\Tests\Integration\RefreshDatabase;
use VOSTPT\Tests\Integration\TestCase;

class IndexEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itFailsToIndexWeatherObservationsDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('GET', route('weather::observations::index'), [], static::INVALID_CONTENT_TYPE_HEADER);

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
    public function itFailsToIndexWeatherObservationsDueToInvalidAcceptHeader(): void
    {
        $response = $this->json('GET', route('weather::observations::index'), [], static::INVALID_ACCEPT_HEADER);

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
    public function itFailsToIndexWeatherObservationsDueToInvalidInput(): void
    {
        $response = $this->json('GET', route('weather::observations::index'), [
            'page' => [
                'number' => 'second',
                'size'   => 'ten',
            ],
            'search' => '',
            'exact'  => 'yes',
            'events' => [
                1,
            ],
            'types' => [
                1,
            ],
            'statuses' => [
                1,
            ],
            'districts' => [
                1,
            ],
            'counties' => [
                1,
            ],
            'stations' => [
                1,
            ],
            'timestamp_from' => '2000-12-31',
            'timestamp_to'   => '2000-01-01',
            'sort'           => 'id',
            'order'          => 'up',
        ], static::VALID_CONTENT_TYPE_HEADER);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                [
                    'detail' => 'The page.number must be an integer.',
                    'meta'   => [
                        'field' => 'page.number',
                    ],
                ],
                [
                    'detail' => 'The page.size must be an integer.',
                    'meta'   => [
                        'field' => 'page.size',
                    ],
                ],
                [
                    'detail' => 'The search must be a string.',
                    'meta'   => [
                        'field' => 'search',
                    ],
                ],
                [
                    'detail' => 'The exact field must be true or false.',
                    'meta'   => [
                        'field' => 'exact',
                    ],
                ],
                [
                    'detail' => 'The timestamp from must be a date before or equal to timestamp to.',
                    'meta'   => [
                        'field' => 'timestamp_from',
                    ],
                ],
                [
                    'detail' => 'The timestamp to must be a date after or equal to timestamp from.',
                    'meta'   => [
                        'field' => 'timestamp_to',
                    ],
                ],
                [
                    'detail' => 'The selected sort is invalid.',
                    'meta'   => [
                        'field' => 'sort',
                    ],
                ],
                [
                    'detail' => 'The selected order is invalid.',
                    'meta'   => [
                        'field' => 'order',
                    ],
                ],
                [
                    'detail' => 'The selected districts.0 is invalid.',
                    'meta'   => [
                        'field' => 'districts.0',
                    ],
                ],
                [
                    'detail' => 'The selected counties.0 is invalid.',
                    'meta'   => [
                        'field' => 'counties.0',
                    ],
                ],
                [
                    'detail' => 'The selected stations.0 is invalid.',
                    'meta'   => [
                        'field' => 'stations.0',
                    ],
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function itSuccessfullyIndexesWeatherObservations(): void
    {
        $district = factory(District::class)->create();
        $county   = factory(County::class)->create([
            'district_id' => $district->getKey(),
        ]);
        $station = factory(WeatherStation::class)->create([
            'county_id' => $county->getKey(),
        ]);

        factory(WeatherObservation::class, 20)->create([
            'station_id' => $station->getKey(),
            'timestamp'  => Carbon::now(),
        ]);

        $response = $this->json('GET', route('weather::observations::index'), [
            'page' => [
                'number' => 2,
                'size'   => 2,
            ],
            'districts' => [
                $district->getKey(),
            ],
            'counties' => [
                $county->getKey(),
            ],
            'stations' => [
                $station->getKey(),
            ],
            'timestamp_from' => Carbon::yesterday()->toDateString(),
            'timestamp_to'   => Carbon::tomorrow()->toDateString(),
            'search'         => '0 1 2 3 4 5 6 7 8 9',
            'sort'           => 'temperature',
            'order'          => 'asc',
        ], static::VALID_CONTENT_TYPE_HEADER);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'links' => [
                'first',
                'last',
                'prev',
                'next',
            ],
            'data' => [
                '*' => [
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
                                'id',
                                'type',
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
            'meta' => [
                'items',
                'total',
            ],
        ]);
    }
}
