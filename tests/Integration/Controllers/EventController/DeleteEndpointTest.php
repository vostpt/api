<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\EventController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Http\Middleware\Authenticate;
use VOSTPT\Models\Event;
use VOSTPT\Models\Occurrence;
use VOSTPT\Models\ProCivOccurrence;
use VOSTPT\Models\Role;
use VOSTPT\Models\User;
use VOSTPT\Tests\Integration\TestCase;

class DeleteEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itFailsToDeleteEventDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('DELETE', route('events::delete', [
            'Event' => 1,
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
    public function itFailsToDeleteEventDueToMissingJwtToken(): void
    {
        $response = $this->json('DELETE', route('events::delete', [
            'Event' => 1,
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
    public function itFailsToDeleteEventDueToRecordNotFound(): void
    {
        $this->withoutMiddleware(Authenticate::class);

        $response = $this->json('DELETE', route('events::delete', [
            'Event' => 1,
        ]), [], [
            'Content-Type' => 'application/vnd.api+json',
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(404);
        $response->assertJson([
            'errors' => [
                [
                    'status' => 404,
                    'detail' => 'Event Not Found',
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function itFailsToDeleteEventDueToAssociatedOccurrences(): void
    {
        $user = factory(User::class)->create()->assign(Role::ADMINISTRATOR);

        $event = factory(Event::class)->create();

        $occurrence = factory(Occurrence::class)->make([
            'event_id' => $event->getKey(),
        ]);

        factory(ProCivOccurrence::class)->create()->occurrence()->save($occurrence);

        $token = auth()->login($user);

        $response = $this->json('DELETE', route('events::delete', [
            'Event' => $event->getKey(),
        ]), [], [
            'Content-Type'  => 'application/vnd.api+json',
            'Authorization' => \sprintf('Bearer %s', $token),
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(403);
        $response->assertJson([
            'errors' => [
                [
                    'status' => 403,
                    'detail' => 'Events with Occurrences cannot be deleted',
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function itSuccessfullyDeletesEvent(): void
    {
        $user = factory(User::class)->create()->assign(Role::ADMINISTRATOR);

        $event = factory(Event::class)->create([
            'name'        => 'Incêndio florestal de Pedrógão Grande',
            'description' => 'O incêndio florestal de Pedrógão Grande, deflagrou a 17 de junho de 2017 no concelho de Pedrógão Grande, no distrito de Leiria.',
            'latitude'    => 39.954211,
            'longitude'   => -8.233333,
            'started_at'  => '2017-06-17 16:51:00',
            'ended_at'    => '2017-06-24 18:54:00',
        ]);

        $this->assertDatabaseHas('events', [
            'name'        => 'Incêndio florestal de Pedrógão Grande',
            'description' => 'O incêndio florestal de Pedrógão Grande, deflagrou a 17 de junho de 2017 no concelho de Pedrógão Grande, no distrito de Leiria.',
            'latitude'    => 39.954211,
            'longitude'   => -8.233333,
            'started_at'  => '2017-06-17 16:51:00',
            'ended_at'    => '2017-06-24 18:54:00',
        ]);

        $token = auth()->login($user);

        $response = $this->json('DELETE', route('events::delete', [
            'Event' => $event->getKey(),
        ]), [], [
            'Content-Type'  => 'application/vnd.api+json',
            'Authorization' => \sprintf('Bearer %s', $token),
        ]);

        $this->assertDatabaseMissing('events', [
            'name'        => 'Incêndio florestal de Pedrógão Grande',
            'description' => 'O incêndio florestal de Pedrógão Grande, deflagrou a 17 de junho de 2017 no concelho de Pedrógão Grande, no distrito de Leiria.',
            'latitude'    => 39.954211,
            'longitude'   => -8.233333,
            'started_at'  => '2017-06-17 16:51:00',
            'ended_at'    => '2017-06-24 18:54:00',
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(200);
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
     * @dataProvider deleteDataProvider
     *
     * @param string $role
     * @param int    $status
     */
    public function itVerifiesRoleAccessPermissionsToDeleteEvent(string $role, int $status): void
    {
        $user = factory(User::class)->create()->assign($role);

        $event = factory(Event::class)->create();

        $token = auth()->login($user);

        $response = $this->json('DELETE', route('events::delete', [
            'Event' => $event->getKey(),
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
    public function deleteDataProvider(): array
    {
        return [
            'Administrator' => [
                Role::ADMINISTRATOR,
                200,
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
