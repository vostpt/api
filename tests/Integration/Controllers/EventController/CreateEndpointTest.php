<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\EventController;

use VOSTPT\Models\EventType;
use VOSTPT\Models\Parish;
use VOSTPT\Models\Role;
use VOSTPT\Models\User;
use VOSTPT\Tests\Integration\RefreshDatabase;
use VOSTPT\Tests\Integration\TestCase;

class CreateEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itFailsToCreateEventDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('POST', route('events::create'));

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
    public function itFailsToCreateEventDueToMissingAccessToken(): void
    {
        $response = $this->json('POST', route('events::create'), [], [
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
    public function itFailsToCreateEventDueToInvalidInput(): void
    {
        $user = factory(User::class)->create()->assign(Role::ADMINISTRATOR);

        $token = auth()->login($user);

        $response = $this->json('POST', route('events::create'), [
            'type'       => 1,
            'parish'     => 1,
            'name'       => \str_repeat('Incêndio florestal de Pedrógão Grande', 10),
            'latitude'   => '39 north',
            'longitude'  => '8 west',
            'started_at' => '2017-06-24 18:54:00',
            'ended_at'   => '2017-06-17 16:51:00',
        ], [
            'Content-Type'  => 'application/vnd.api+json',
            'Authorization' => \sprintf('Bearer %s', $token),
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                [
                    'detail' => 'The name may not be greater than 255 characters.',
                    'meta'   => [
                        'field' => 'name',
                    ],
                ],
                [
                    'detail' => 'The description field is required.',
                    'meta'   => [
                        'field' => 'description',
                    ],
                ],
                [
                    'detail' => 'The latitude must be a number.',
                    'meta'   => [
                        'field' => 'latitude',
                    ],
                ],
                [
                    'detail' => 'The longitude must be a number.',
                    'meta'   => [
                        'field' => 'longitude',
                    ],
                ],
                [
                    'detail' => 'The started at must be a date before or equal to ended at.',
                    'meta'   => [
                        'field' => 'started_at',
                    ],
                ],
                [
                    'detail' => 'The ended at must be a date after or equal to started at.',
                    'meta'   => [
                        'field' => 'ended_at',
                    ],
                ],
                [
                    'detail' => 'The selected type is invalid.',
                    'meta'   => [
                        'field' => 'type',
                    ],
                ],
                [
                    'detail' => 'The selected parish is invalid.',
                    'meta'   => [
                        'field' => 'parish',
                    ],
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function itSuccessfullyCreatesEvent(): void
    {
        $user = factory(User::class)->create()->assign(Role::ADMINISTRATOR);

        $eventType = factory(EventType::class)->create();
        $parish    = factory(Parish::class)->create();

        $this->assertDatabaseMissing('events', [
            'type_id'     => $eventType->getKey(),
            'parish_id'   => $parish->getKey(),
            'name'        => 'Incêndio florestal de Pedrógão Grande',
            'description' => 'O incêndio florestal de Pedrógão Grande, deflagrou a 17 de junho de 2017 no concelho de Pedrógão Grande, no distrito de Leiria.',
            'latitude'    => 39.954211,
            'longitude'   => -8.233333,
            'started_at'  => '2017-06-17 16:51:00',
            'ended_at'    => '2017-06-24 18:54:00',
        ]);

        $token = auth()->login($user);

        $response = $this->json('POST', route('events::create'), [
            'type'        => $eventType->getKey(),
            'parish'      => $parish->getKey(),
            'name'        => 'Incêndio florestal de Pedrógão Grande',
            'description' => 'O incêndio florestal de Pedrógão Grande, deflagrou a 17 de junho de 2017 no concelho de Pedrógão Grande, no distrito de Leiria.',
            'latitude'    => 39.954211,
            'longitude'   => -8.233333,
            'started_at'  => '2017-06-17 16:51:00',
            'ended_at'    => '2017-06-24 18:54:00',
        ], [
            'Content-Type'  => 'application/vnd.api+json',
            'Authorization' => \sprintf('Bearer %s', $token),
        ]);

        $this->assertDatabaseHas('events', [
            'type_id'     => $eventType->getKey(),
            'parish_id'   => $parish->getKey(),
            'name'        => 'Incêndio florestal de Pedrógão Grande',
            'description' => 'O incêndio florestal de Pedrógão Grande, deflagrou a 17 de junho de 2017 no concelho de Pedrógão Grande, no distrito de Leiria.',
            'latitude'    => 39.954211,
            'longitude'   => -8.233333,
            'started_at'  => '2017-06-17 16:51:00',
            'ended_at'    => '2017-06-24 18:54:00',
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
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
                'relationships' => [
                    'type' => [
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
                    'links' => [
                        'self',
                    ],
                ],
            ],
        ]);
    }

    /**
     * @test
     * @dataProvider createDataProvider
     *
     * @param string $role
     * @param int    $status
     */
    public function itVerifiesRoleAccessPermissionsToCreateEvent(string $role, int $status): void
    {
        $user = factory(User::class)->create()->assign($role);

        $token = auth()->login($user);

        $response = $this->json('POST', route('events::create'), [], [
            'Content-Type'  => 'application/vnd.api+json',
            'Authorization' => \sprintf('Bearer %s', $token),
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus($status);
    }

    /**
     * @return array
     */
    public function createDataProvider(): array
    {
        return [
            'Administrator' => [
                Role::ADMINISTRATOR,
                422,
            ],
            'Moderator' => [
                Role::MODERATOR,
                403,
            ],
            'Contributor' => [
                Role::CONTRIBUTOR,
                403,
            ],
        ];
    }
}
