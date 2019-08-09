<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\ParishController;

use VOSTPT\Models\Parish;
use VOSTPT\Tests\Integration\RefreshDatabase;
use VOSTPT\Tests\Integration\TestCase;

class ViewEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itFailsToViewParishDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('GET', route('parishes::view', [
            'Parish' => 1,
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
    public function itFailsToViewParishDueToInvalidAcceptHeader(): void
    {
        $response = $this->json('GET', route('parishes::view', [
            'Parish' => 1,
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
    public function itFailsToViewParishDueToRecordNotFound(): void
    {
        $response = $this->json('GET', route('parishes::view', [
            'Parish' => 1,
        ]), [], static::VALID_CONTENT_TYPE_HEADER);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(404);
        $response->assertJson([
            'errors' => [
                [
                    'status' => 404,
                    'detail' => 'Parish Not Found',
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function itSuccessfullyViewsParish(): void
    {
        $parish = factory(Parish::class)->create();

        $response = $this->json('GET', route('parishes::view', [
            'Parish' => $parish->getKey(),
        ]), [], static::VALID_CONTENT_TYPE_HEADER);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'type',
                'id',
                'attributes' => [
                    'code',
                    'name',
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
                'links' => [
                    'self',
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
                    'links' => [
                        'self',
                    ],
                ],
            ],
        ]);
    }
}
