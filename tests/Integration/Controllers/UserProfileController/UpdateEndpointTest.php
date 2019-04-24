<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\UserProfileController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use VOSTPT\Models\Role;
use VOSTPT\Models\User;
use VOSTPT\Tests\Integration\TestCase;

class UpdateEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itFailsToUpdateProfileDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('PATCH', route('users::profile::update'));

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
    public function itFailsToUpdateProfileDueToMissingJwtToken(): void
    {
        $response = $this->json('PATCH', route('users::profile::update'), [], [
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
    public function itFailsToUpdateProfileDueToValidation(): void
    {
        $user = factory(User::class)->create()->assign(Role::ADMINISTRATOR);

        $token = auth()->login($user);

        $response = $this->json('PATCH', route('users::profile::update'), [
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
    public function itSuccessfullyUpdatesProfile(): void
    {
        $user = factory(User::class)->create([
            'name'    => 'Alberto',
            'surname' => 'Caeiro',
            'email'   => 'alberto.caeiro@vost.pt',
        ])->assign(Role::ADMINISTRATOR);

        $this->assertDatabaseHas('users', [
            'name'    => 'Alberto',
            'surname' => 'Caeiro',
            'email'   => 'alberto.caeiro@vost.pt',
        ]);

        $this->assertDatabaseMissing('users', [
            'name'    => 'Fernando',
            'surname' => 'Pessoa',
            'email'   => 'fernando.pessoa@vost.pt',
        ]);

        $token = auth()->login($user);

        $response = $this->json('PATCH', route('users::profile::update'), [
            'name'                  => 'Fernando',
            'surname'               => 'Pessoa',
            'email'                 => 'fernando.pessoa@vost.pt',
            'password'              => 'absinto',
            'password_confirmation' => 'absinto',
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
    public function itVerifiesRoleAccessPermissionsToUpdateProfile(string $role, int $status): void
    {
        $user = factory(User::class)->create()->assign($role);

        $token = auth()->login($user);

        $response = $this->json('PATCH', route('users::profile::update'), [], [
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
                200,
            ],
        ];
    }
}
