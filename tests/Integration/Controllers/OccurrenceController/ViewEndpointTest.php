<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\OccurrenceController;

use VOSTPT\Models\Occurrence;
use VOSTPT\Models\ProCivOccurrence;
use VOSTPT\Tests\Integration\RefreshDatabaseWithRoles;
use VOSTPT\Tests\Integration\TestCase;

class ViewEndpointTest extends TestCase
{
    use RefreshDatabaseWithRoles;

    /**
     * @test
     */
    public function itFailsToViewOccurrenceDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('GET', route('occurrences::view', [
            'Occurrence' => 1,
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
    public function itFailsToViewOccurrenceDueToInvalidAcceptHeader(): void
    {
        $response = $this->json('GET', route('occurrences::view', [
            'Occurrence' => 1,
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
    public function itFailsToViewOccurrenceDueToRecordNotFound(): void
    {
        $response = $this->json('GET', route('occurrences::view', [
            'Occurrence' => 1,
        ]), [], static::VALID_CONTENT_TYPE_HEADER);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(404);
        $response->assertJson([
            'errors' => [
                [
                    'status' => 404,
                    'detail' => 'Occurrence Not Found',
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function itSuccessfullyViewsOccurrenceWithProCivSource(): void
    {
        $occurrence = factory(Occurrence::class)->make();

        factory(ProCivOccurrence::class)->create()->parent()->save($occurrence);

        $response = $this->json('GET', route('occurrences::view', [
            'Occurrence' => $occurrence->getKey(),
        ]), [], static::VALID_CONTENT_TYPE_HEADER);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'type',
                'id',
                'attributes' => [
                    'locality',
                    'latitude',
                    'longitude',
                    'started_at',
                    'ended_at',
                    'created_at',
                    'updated_at',
                ],
                'relationships' => [
                    'event' => [
                        'data' => [
                            'type',
                            'id',
                        ],
                    ],
                    'type' => [
                        'data' => [
                            'type',
                            'id',
                        ],
                    ],
                    'status' => [
                        'data' => [
                            'type',
                            'id',
                        ],
                    ],
                    'parish' => [
                        'data' => [
                            'type',
                            'id',
                        ],
                    ],
                    'source' => [
                        'data' => [
                            'type',
                            'id',
                        ],
                    ],
                ],
                'links' => [
                    'self',
                ],
            ],
            'included' => [
                [
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
                [
                    'type',
                    'id',
                    'attributes' => [
                        'code',
                        'name',
                        'created_at',
                        'updated_at',
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
                [
                    'type',
                    'id',
                    'attributes' => [
                        'remote_id',
                        'ground_assets',
                        'ground_operatives',
                        'aerial_assets',
                        'aerial_operatives',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ],
        ]);
    }
}
