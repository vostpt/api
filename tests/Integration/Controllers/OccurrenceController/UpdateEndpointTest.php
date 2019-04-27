<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\OccurrenceController;

use Tymon\JWTAuth\Http\Middleware\Authenticate;
use VOSTPT\Models\Event;
use VOSTPT\Models\Occurrence;
use VOSTPT\Models\ProCivOccurrence;
use VOSTPT\Models\Role;
use VOSTPT\Models\User;
use VOSTPT\Tests\Integration\RefreshDatabase;
use VOSTPT\Tests\Integration\TestCase;

class UpdateEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itFailsToUpdateOccurrenceDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('PATCH', route('occurrences::update', [
            'Occurrence' => 1,
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
    public function itFailsToUpdateOccurrenceDueToMissingAccessToken(): void
    {
        $response = $this->json('PATCH', route('occurrences::update', [
            'Occurrence' => 1,
        ]), [], [
            'Content-Type' => 'application/vnd.api+json',
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(401);
        $response->assertJson([
            'errors' => [
                [
                    'status' => 401,
                    'detail' => 'Token not provided',
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function itFailsToUpdateOccurrenceDueToRecordNotFound(): void
    {
        $this->withoutMiddleware(Authenticate::class);

        $response = $this->json('PATCH', route('occurrences::update', [
            'Occurrence' => 1,
        ]), [], [
            'Content-Type' => 'application/vnd.api+json',
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(404);
        $response->assertJson([
            'errors' => [
                [
                    'status' => 404,
                    'detail' => 'Occurrence Not Found',
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function itFailsToUpdateOccurrenceDueToInvalidInput(): void
    {
        $user = factory(User::class)->create()->assign(Role::ADMINISTRATOR);

        $occurrence = factory(Occurrence::class)->make([
            'event_id' => null,
        ]);

        factory(ProCivOccurrence::class)->create()->occurrence()->save($occurrence);

        $token = auth()->login($user);

        $response = $this->json('PATCH', route('occurrences::update', [
            'Occurrence' => $occurrence->getKey(),
        ]), [
            'event' => 1,
        ], [
            'Content-Type'  => 'application/vnd.api+json',
            'Authorization' => \sprintf('Bearer %s', $token),
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                [
                    'detail' => 'The selected event is invalid.',
                    'meta'   => [
                        'field' => 'event',
                    ],
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function itSuccessfullyUpdatesOccurrence(): void
    {
        $user = factory(User::class)->create()->assign(Role::ADMINISTRATOR);

        $event = factory(Event::class)->create();

        $occurrence = factory(Occurrence::class)->make();

        factory(ProCivOccurrence::class)->create()->occurrence()->save($occurrence);

        $this->assertDatabaseMissing('occurrences', [
            'event_id' => $event->getKey(),
        ]);

        $token = auth()->login($user);

        $response = $this->json('PATCH', route('occurrences::update', [
            'Occurrence' => $occurrence->getKey(),
        ]), [
            'event' => $event->getKey(),
        ], [
            'Content-Type'  => 'application/vnd.api+json',
            'Authorization' => \sprintf('Bearer %s', $token),
        ]);


        $this->assertDatabaseHas('occurrences', [
            'event_id' => $event->getKey(),
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'type',
                'id',
                'attributes' => [
                    'locality',
                    'latitude',
                    'longitude',
                    'started_at',
                    'ended_at',
                    'created_at',
                    'updated_at',
                ],
                'relationships' => [
                    'event' => [
                        'data' => [
                            'type',
                            'id',
                        ],
                    ],
                    'parish' => [
                        'data' => [
                            'type',
                            'id',
                        ],
                    ],
                    'source' => [
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
                        'name',
                        'description',
                        'latitude',
                        'longitude',
                        'started_at',
                        'ended_at',
                        'created_at',
                        'updated_at',
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
                [
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

    /**
     * @test
     * @dataProvider updateDataProvider
     *
     * @param string $role
     * @param int    $status
     */
    public function itVerifiesRoleAccessPermissionsToUpdateOccurrence(string $role, int $status): void
    {
        $user = factory(User::class)->create()->assign($role);

        $occurrence = factory(Occurrence::class)->make();

        factory(ProCivOccurrence::class)->create()->occurrence()->save($occurrence);

        $token = auth()->login($user);

        $response = $this->json('PATCH', route('occurrences::update', [
            'Occurrence' => $occurrence->getKey(),
        ]), [], [
            'Content-Type'  => 'application/vnd.api+json',
            'Authorization' => \sprintf('Bearer %s', $token),
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus($status);
    }

    /**
     * @return array
     */
    public function updateDataProvider(): array
    {
        return [
            'Administrator' => [
                Role::ADMINISTRATOR,
                200,
            ],
            'Moderator' => [
                Role::MODERATOR,
                200,
            ],
            'Contributor' => [
                Role::CONTRIBUTOR,
                403,
            ],
        ];
    }
}
