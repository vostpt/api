<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\OccurrenceController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use VOSTPT\Models\Occurrence;
use VOSTPT\Models\ProCivOccurrence;
use VOSTPT\Tests\Integration\TestCase;

class ViewEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itFailsToViewOccurrenceDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('GET', route('occurrences::view', [
            'occurrence' => 1,
        ]));

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(415);
        $response->assertJson([
            'errors' => [
                [
                    'status' => 415,
                    'detail' => 'Wrong media type',
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
            'occurrence' => 1,
        ]), [], [
            'Content-Type' => 'application/vnd.api+json',
        ]);

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
    public function itSuccessfullyViewsOccurrence(): void
    {
        $occurrence       = factory(Occurrence::class)->make();
        $proCivOccurrence = factory(ProCivOccurrence::class)->create();
        $proCivOccurrence->occurrence()->save($occurrence);

        $response = $this->json('GET', route('occurrences::view', [
            'occurrence' => $occurrence->getKey(),
        ]), [], [
            'Content-Type' => 'application/vnd.api+json',
        ]);

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
                    'parish' => [
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
                    'links' => [
                        'self',
                    ],
                ],
                [
                    'type',
                    'id',
                    'attributes' => [
                        'remote_id',
                        'ground_assets_involved',
                        'ground_operatives_involved',
                        'aerial_assets_involved',
                        'aerial_operatives_involved',
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