<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\DistrictController;

use VOSTPT\Models\District;
use VOSTPT\Tests\Integration\RefreshDatabaseWithRoles;
use VOSTPT\Tests\Integration\TestCase;

class IndexEndpointTest extends TestCase
{
    use RefreshDatabaseWithRoles;

    /**
     * @test
     */
    public function itFailsToIndexDistrictsDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('GET', route('districts::index'), [], static::INVALID_CONTENT_TYPE_HEADER);

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
    public function itFailsToIndexDistrictsDueToInvalidAcceptHeader(): void
    {
        $response = $this->json('GET', route('districts::index'), [], static::INVALID_ACCEPT_HEADER);

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
    public function itFailsToIndexDistrictsDueToInvalidInput(): void
    {
        $response = $this->json('GET', route('districts::index'), [
            'page' => [
                'number' => 'second',
                'size'   => 'ten',
            ],
            'ids' => [
                123,
            ],
            'search' => '',
            'exact'  => 'yes',
            'sort'   => 'id',
            'order'  => 'up',
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
                    'detail' => 'The selected ids.0 is invalid.',
                    'meta'   => [
                        'field' => 'ids.0',
                    ],
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function itSuccessfullyIndexesDistricts(): void
    {
        factory(District::class, 20)->create();

        $response = $this->json('GET', route('districts::index'), [
            'page' => [
                'number' => 2,
                'size'   => 2,
            ],
            'ids'    => \range(1, 20),
            'search' => '0 1 2 3 4 5 6 7 8 9',
            'sort'   => 'code',
            'order'  => 'asc',
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
