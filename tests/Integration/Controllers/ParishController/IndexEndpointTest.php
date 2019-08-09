<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\ParishController;

use VOSTPT\Models\County;
use VOSTPT\Models\Parish;
use VOSTPT\Tests\Integration\RefreshDatabase;
use VOSTPT\Tests\Integration\TestCase;

class IndexEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itFailsToIndexParishesDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('GET', route('parishes::index'), [], static::INVALID_CONTENT_TYPE_HEADER);

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
    public function itFailsToIndexParishesDueToInvalidAcceptHeader(): void
    {
        $response = $this->json('GET', route('parishes::index'), [], static::INVALID_ACCEPT_HEADER);

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
    public function itFailsToIndexParishesDueToInvalidInput(): void
    {
        $response = $this->json('GET', route('parishes::index'), [
            'page' => [
                'number' => 'second',
                'size'   => 'ten',
            ],
            'search'   => '',
            'exact'    => 'yes',
            'counties' => [
                1,
            ],
            'sort'  => 'id',
            'order' => 'up',
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
                    'detail' => 'The selected counties.0 is invalid.',
                    'meta'   => [
                        'field' => 'counties.0',
                    ],
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function itSuccessfullyIndexesParishes(): void
    {
        $county = factory(County::class)->create();

        factory(Parish::class, 20)->create([
            'county_id' => $county->getKey(),
        ]);

        $response = $this->json('GET', route('parishes::index'), [
            'page' => [
                'number' => 2,
                'size'   => 2,
            ],
            'search'   => '0 1 2 3 4 5 6 7 8 9',
            'counties' => [
                $county->getKey(),
            ],
            'sort'  => 'code',
            'order' => 'asc',
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
