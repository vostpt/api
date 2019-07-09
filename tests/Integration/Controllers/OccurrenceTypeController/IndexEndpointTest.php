<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\OccurrenceTypeController;

use VOSTPT\Models\OccurrenceSpecies;
use VOSTPT\Models\OccurrenceType;
use VOSTPT\Tests\Integration\RefreshDatabase;
use VOSTPT\Tests\Integration\TestCase;

class IndexEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itFailsToIndexOccurrenceTypesDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('GET', route('occurrences::types::index'));

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
    public function itFailsToIndexOccurrenceTypesDueToInvalidInput(): void
    {
        $response = $this->json('GET', route('occurrences::types::index'), [
            'page' => [
                'number' => 'second',
                'size'   => 'ten',
            ],
            'search'  => '',
            'exact'   => 'yes',
            'species' => [
                1,
            ],
            'sort'  => 'id',
            'order' => 'up',
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
                [
                    'detail' => 'The selected species.0 is invalid.',
                    'meta'   => [
                        'field' => 'species.0',
                    ],
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function itSuccessfullyIndexesOccurrenceTypes(): void
    {
        $species = factory(OccurrenceSpecies::class)->create();

        factory(OccurrenceType::class, 20)->create([
            'species_id' => $species->getKey(),
        ]);

        $response = $this->json('GET', route('occurrences::types::index'), [
            'page' => [
                'number' => 2,
                'size'   => 2,
            ],
            'species' => [
                $species->getKey(),
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
                    'relationships' => [
                        'species' => [
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
