<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\IpmaWarningController;

use VOSTPT\Tests\Integration\RefreshDatabase;
use VOSTPT\Tests\Integration\TestCase;

class IndexEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itFailsToIndexIpmaWarningDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('GET', route('ipma::warnings:index'));

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
    public function itSuccessfullyIndexesIpmaWarning(): void
    {
        $response = $this->json('GET', route('ipma::warnings::index'), [], [
            'Content-Type' => 'application/vnd.api+json',
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'type',
                    'id',
                    'attributes' => [
                        'uuid',
                        'text',
                        'awareness_type_name',
                        'awareness_level_id',
                        'zone_name',
                        'area_id',
                        'area_name',
                        'started_at',
                        'ended_at',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ],
        ]);
    }
}
