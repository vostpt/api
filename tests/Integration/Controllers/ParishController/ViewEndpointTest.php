<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\ParishController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use VOSTPT\Models\Parish;
use VOSTPT\Tests\Integration\TestCase;

class ViewEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itFailsToViewCountyDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('GET', route('parishes::view', [
            'parish' => 1,
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
    public function itFailsToViewCountyDueToRecordNotFound(): void
    {
        $response = $this->json('GET', route('parishes::view', [
            'parish' => 1,
        ]), [], [
            'Content-Type' => 'application/vnd.api+json',
        ]);

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
    public function itSuccessfullyViewsCounty(): void
    {
        $parish = factory(Parish::class)->create();

        $response = $this->json('GET', route('parishes::view', [
            'parish' => $parish->getKey(),
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
