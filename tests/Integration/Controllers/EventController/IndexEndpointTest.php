<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\EventController;

use VOSTPT\Models\Event;
use VOSTPT\Models\EventType;
use VOSTPT\Models\Parish;
use VOSTPT\Tests\Integration\RefreshDatabaseWithRoles;
use VOSTPT\Tests\Integration\TestCase;

class IndexEndpointTest extends TestCase
{
    use RefreshDatabaseWithRoles;

    /**
     * @test
     */
    public function itFailsToIndexEventsDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('GET', route('events::index'), [], static::INVALID_CONTENT_TYPE_HEADER);

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
    public function itFailsToIndexEventsDueToInvalidAcceptHeader(): void
    {
        $response = $this->json('GET', route('events::index'), [], static::INVALID_ACCEPT_HEADER);

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
    public function itFailsToIndexEventsDueToInvalidInput(): void
    {
        $response = $this->json('GET', route('events::index'), [
            'page' => [
                'number' => 'second',
                'size'   => 'ten',
            ],
            'ids' => [
                123,
            ],
            'search' => '',
            'exact'  => 'yes',
            'types'  => [
                1,
            ],
            'parishes' => [
                1,
            ],
            'latitude'  => 'north',
            'longitude' => 'east',
            'radius'    => 500,
            'sort'      => 'id',
            'order'     => 'up',
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
                    'detail' => 'The latitude must be a number.',
                    'meta'   => [
                        'field' => 'latitude',
                    ],
                ],
                [
                    'detail' => 'The longitude must be a number.',
                    'meta'   => [
                        'field' => 'longitude',
                    ],
                ],
                [
                    'detail' => 'The radius must be between 1 and 200.',
                    'meta'   => [
                        'field' => 'radius',
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
                    'detail' => 'The selected ids.0 is invalid.',
                    'meta'   => [
                        'field' => 'ids.0',
                    ],
                ],
                [
                    'detail' => 'The selected types.0 is invalid.',
                    'meta'   => [
                        'field' => 'types.0',
                    ],
                ],
                [
                    'detail' => 'The selected parishes.0 is invalid.',
                    'meta'   => [
                        'field' => 'parishes.0',
                    ],
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function itSuccessfullyIndexesEvents(): void
    {
        $type   = factory(EventType::class)->create();
        $parish = factory(Parish::class)->create();

        $latitude  = 38.166749;
        $longitude = -7.891448;

        $ids = factory(Event::class, 20)->create([
            'type_id'   => $type->getKey(),
            'parish_id' => $parish->getKey(),
            'latitude'  => $latitude,
            'longitude' => $longitude,
        ])->pluck('id')->all();

        $response = $this->json('GET', route('events::index'), [
            'page' => [
                'number' => 2,
                'size'   => 2,
            ],
            'ids'    => $ids,
            'search' => '0 1 2 3 4 5 6 7 8 9',
            'types'  => [
                $type->getKey(),
            ],
            'parishes' => [
                $parish->getKey(),
            ],
            'latitude'  => $latitude,
            'longitude' => $longitude,
            'radius'    => 1,
            'sort'      => 'description',
            'order'     => 'asc',
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
                        'name',
                        'description',
                        'latitude',
                        'longitude',
                        'started_at',
                        'ended_at',
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
