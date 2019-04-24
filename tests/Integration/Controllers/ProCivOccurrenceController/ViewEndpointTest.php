<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\ProCivOccurrenceController;

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
    public function itFailsToViewProCivOccurrenceDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('GET', route('occurrences::prociv::view', [
            'ProCivOccurrence' => 1,
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
    public function itFailsToViewProCivOccurrenceDueToRecordNotFound(): void
    {
        $response = $this->json('GET', route('occurrences::prociv::view', [
            'ProCivOccurrence' => 1,
        ]), [], [
            'Content-Type' => 'application/vnd.api+json',
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(404);
        $response->assertJson([
            'errors' => [
                [
                    'status' => 404,
                    'detail' => 'ProCivOccurrence Not Found',
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function itSuccessfullyViewsProCivOccurrence(): void
    {
        $occurrence       = factory(Occurrence::class)->make();
        $proCivOccurrence = factory(ProCivOccurrence::class)->create();
        $proCivOccurrence->occurrence()->save($occurrence);

        $response = $this->json('GET', route('occurrences::prociv::view', [
            'ProCivOccurrence' => $occurrence->getKey(),
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
                    'remote_id',
                    'ground_assets_involved',
                    'ground_operatives_involved',
                    'aerial_assets_involved',
                    'aerial_operatives_involved',
                    'created_at',
                    'updated_at',
                ],
                'relationships' => [
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
            ],
        ]);
    }
}
