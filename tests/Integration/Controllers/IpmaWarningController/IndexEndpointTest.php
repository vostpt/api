<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\IpmaWarningController;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Ramsey\Uuid\Uuid;
use VOSTPT\Models\County;
use VOSTPT\Tests\Integration\RefreshDatabaseWithRoles;
use VOSTPT\Tests\Integration\TestCase;

class IndexEndpointTest extends TestCase
{
    use RefreshDatabaseWithRoles;

    /**
     * @test
     */
    public function itFailsToIndexWarningsDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('GET', route('ipma::warnings::index'), [], static::INVALID_CONTENT_TYPE_HEADER);

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
    public function itFailsToIndexWarningsDueToInvalidAcceptHeader(): void
    {
        $response = $this->json('GET', route('ipma::warnings::index'), [], static::INVALID_ACCEPT_HEADER);

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
    public function itSuccessfullyIndexesWarnings(): void
    {
        $county = factory(County::class)->create();

        Cache::forever('ipma_warnings', [
                [
                    'text'              => 'Rajadas até 75 km/h, em especial no litoral e nas terras altas.',
                    'awarenessTypeName' => 'Vento',
                    'idAreaAviso'       => 'LSB',
                    'awarenessLevelID'  => 'yellow',
                    'id'                => Uuid::uuid4(),
                    'county'            => $county,
                    'started_at'        => Carbon::now(),
                    'ended_at'          => Carbon::now()->addHour(),
                ],
            ]);

        $response = $this->json('GET', route('ipma::warnings::index'), [], static::VALID_CONTENT_TYPE_HEADER);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'type',
                    'id',
                    'attributes' => [
                        'description',
                        'awareness_type_name',
                        'awareness_level',
                        'started_at',
                        'ended_at',
                    ],
                    'relationships' => [
                        'county' => [
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
                    'relationships' => [
                        'district' => [
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
