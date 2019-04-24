<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\DistrictController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use VOSTPT\Models\District;
use VOSTPT\Tests\Integration\TestCase;

class ViewEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itFailsToViewDistrictDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('GET', route('districts::view', [
            'District' => 1,
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
    public function itFailsToViewDistrictDueToRecordNotFound(): void
    {
        $response = $this->json('GET', route('districts::view', [
            'District' => 1,
        ]), [], [
            'Content-Type' => 'application/vnd.api+json',
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(404);
        $response->assertJson([
            'errors' => [
                [
                    'status' => 404,
                    'detail' => 'District Not Found',
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function itSuccessfullyViewsDistrict(): void
    {
        $district = factory(District::class)->create();

        $response = $this->json('GET', route('districts::view', [
            'District' => $district->getKey(),
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
                'links' => [
                    'self',
                ],
            ],
        ]);
    }
}
