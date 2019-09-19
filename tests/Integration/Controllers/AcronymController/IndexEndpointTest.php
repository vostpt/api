<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\AcronymController;

use VOSTPT\Models\Acronym;
use VOSTPT\Tests\Integration\RefreshDatabaseWithRoles;
use VOSTPT\Tests\Integration\TestCase;

class IndexEndpointTest extends TestCase
{
    use RefreshDatabaseWithRoles;

    /**
     * @test
     */
    public function itFailsToIndexAcronymsDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('GET', route('acronyms::index'), [], static::INVALID_CONTENT_TYPE_HEADER);

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
    public function itFailsToIndexAcronymsDueToInvalidAcceptHeader(): void
    {
        $response = $this->json('GET', route('acronyms::index'), [], static::INVALID_ACCEPT_HEADER);

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
    public function itFailsToIndexAcronymsDueToInvalidInput(): void
    {
        $response = $this->json('GET', route('acronyms::index'), [
            'page' => [
                'number' => 'second',
                'size'   => 'ten',
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
            ],
        ]);
    }

    /**
     * @test
     */
    public function itSuccessfullyIndexesAcronyms(): void
    {
        factory(Acronym::class, 20)->create();

        $response = $this->json('GET', route('acronyms::index'), [
            'page' => [
                'number' => 2,
                'size'   => 2,
            ],
            'search' => 'a b c d e f g i j k l m n o p q r s',
            'sort'   => 'initials',
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
                        'initials',
                        'meaning',
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

    /**
     * @test
     * @dataProvider indexDataProvider
     *
     * @param bool $exact
     * @param int  $results
     */
    public function itSuccessfullyIndexesWithAndWithoutExactMatch(bool $exact, int $results): void
    {
        factory(Acronym::class)->create([
            'initials' => 'ANAC',
        ]);
        factory(Acronym::class)->create([
            'initials' => 'ANACOM',
        ]);

        $response = $this->json('GET', route('acronyms::index'), [
            'search' => 'anac',
            'exact'  => $exact,
            'sort'   => 'initials',
            'order'  => 'asc',
        ], static::VALID_CONTENT_TYPE_HEADER);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(200);
        $response->assertJsonCount($results, 'data');
    }

    /**
     * @return array
     */
    public function indexDataProvider(): array
    {
        return [
            'Wildcard match search' => [
                false,
                2,
            ],
            'Exact match search' => [
                true,
                1,
            ],
        ];
    }
}
