<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\OccurrenceStatusController;

use VOSTPT\Models\OccurrenceStatus;
use VOSTPT\Tests\Integration\RefreshDatabase;
use VOSTPT\Tests\Integration\TestCase;

class IndexEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itFailsToIndexOccurrenceStatusesDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('GET', route('occurrences::statuses::index'));

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
    public function itFailsToIndexOccurrenceStatusesDueToInvalidInput(): void
    {
        $response = $this->json('GET', route('occurrences::statuses::index'), [
            'page' => [
                'number' => 'second',
                'size'   => 'ten',
            ],
            'search' => '',
            'exact'  => 'yes',
            'sort'   => 'id',
            'order'  => 'up',
        ], [
            'Content-Type' => 'application/vnd.api+json',
        ]);

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
            ],
        ]);
    }

    /**
     * @test
     */
    public function itSuccessfullyIndexesOccurrenceStatuses(): void
    {
        factory(OccurrenceStatus::class, 20)->create();

        $response = $this->json('GET', route('occurrences::statuses::index'), [
            'page' => [
                'number' => 2,
                'size'   => 2,
            ],
            'search' => '0 1 2 3 4 5 6 7 8 9',
            'sort'   => 'name',
            'order'  => 'asc',
        ], [
            'Content-Type' => 'application/vnd.api+json',
        ]);

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
                        'code',
                        'name',
                        'created_at',
                        'updated_at',
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