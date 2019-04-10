<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\UserController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Http\Middleware\Authenticate;
use VOSTPT\Models\Role;
use VOSTPT\Models\User;
use VOSTPT\Tests\Integration\TestCase;

class UpdateEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itFailsToUpdateUserDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('PATCH', route('users::update', [
            'user' => 1,
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
    public function itFailsToUpdateUserDueToMissingJwtToken(): void
    {
        $response = $this->json('PATCH', route('users::update', [
            'user' => 1,
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
    public function itFailsToUpdateUserDueToRecordNotFound(): void
    {
        $this->withoutMiddleware(Authenticate::class);

        $response = $this->json('PATCH', route('users::update', [
            'user' => 1,
        ]), [], [
            'Content-Type' => 'application/vnd.api+json',
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(404);
        $response->assertJson([
            'errors' => [
                [
                    'status' => 404,
                    'detail' => 'User Not Found',
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function itFailsToUpdateUserWhenSelfUpdating(): void
    {
        $user = factory(User::class)->create()->assign(Role::ADMIN);

        $token = auth()->login($user);

        $response = $this->json('PATCH', route('users::update', [
            'user' => $user->id,
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
                    'detail' => 'User cannot self update',
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function itFailsToUpdateUserDueToValidation(): void
    {
        $user = factory(User::class)->create()->assign(Role::ADMIN);

        $anotherUser = factory(User::class)->create();

        $token = auth()->login($user);

        $response = $this->json('PATCH', route('users::update', [
            'user' => $anotherUser->id,
        ]), [
            'name'                  => \str_repeat('name', 70),
            'surname'               => \str_repeat('surname', 40),
            'email'                 => 'invalid at email dot tld',
            'password'              => 'secret',
            'password_confirmation' => 'code',
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
                    'detail' => 'The surname may not be greater than 255 characters.',
                    'meta'   => [
                        'field' => 'surname',
                    ],
                ],
                [
                    'detail' => 'The email must be a valid email address.',
                    'meta'   => [
                        'field' => 'email',
                    ],
                ],
                [
                    'detail' => 'The password confirmation does not match.',
                    'meta'   => [
                        'field' => 'password',
                    ],
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function itSuccessfullyUpdatesUser(): void
    {
        $user = factory(User::class)->create()->assign(Role::ADMIN);

        $anotherUser = factory(User::class)->create([
            'name'    => 'Alberto',
            'surname' => 'Caeiro',
            'email'   => 'alberto.caeiro@vost.pt',
        ])->assign(Role::WRITER);

        $this->assertDatabaseMissing('users', [
            'name'    => 'Fernando',
            'surname' => 'Pessoa',
            'email'   => 'fernando.pessoa@vost.pt',
        ]);

        $this->assertDatabaseHas('users', [
            'name'    => 'Alberto',
            'surname' => 'Caeiro',
            'email'   => 'alberto.caeiro@vost.pt',
        ]);

        $token = auth()->login($user);

        $response = $this->json('PATCH', route('users::update', [
            'user' => $anotherUser->id,
        ]), [
            'name'    => 'Fernando',
            'surname' => 'Pessoa',
            'email'   => 'fernando.pessoa@vost.pt',
        ], [
            'Content-Type'  => 'application/vnd.api+json',
            'Authorization' => \sprintf('Bearer %s', $token),
        ]);

        $this->assertDatabaseMissing('users', [
            'name'    => 'Alberto',
            'surname' => 'Caeiro',
            'email'   => 'alberto.caeiro@vost.pt',
        ]);

        $this->assertDatabaseHas('users', [
            'name'    => 'Fernando',
            'surname' => 'Pessoa',
            'email'   => 'fernando.pessoa@vost.pt',
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'type',
                'id',
                'attributes' => [
                    'email',
                    'name',
                    'surname',
                    'created_at',
                    'updated_at',
                ],
                'relationships' => [
                    'roles' => [
                        'data' => [
                            '*' => [
                                'type',
                                'id',
                            ],
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
                        'title',
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
    public function itVerifiesRoleAccessPermissionsToUpdateUser(string $role, int $status): void
    {
        $user = factory(User::class)->create()->assign($role);

        $anotherUser = factory(User::class)->create();

        $token = auth()->login($user);

        $response = $this->json('PATCH', route('users::update', [
            'user' => $anotherUser->id,
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
            'Admin' => [
                Role::ADMIN,
                200,
            ],
            'Writer' => [
                Role::WRITER,
                403,
            ],
            'Reader' => [
                Role::READER,
                403,
            ],
        ];
    }
}
