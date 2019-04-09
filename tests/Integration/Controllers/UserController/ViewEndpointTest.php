<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\UserController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Http\Middleware\Authenticate;
use VOSTPT\Models\Role;
use VOSTPT\Models\User;
use VOSTPT\Tests\Integration\TestCase;

class ViewEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itFailsToViewUserDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('GET', route('users::view', [
            'user' => 123,
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
    public function itFailsToViewUserDueToMissingJwtToken(): void
    {
        $response = $this->json('GET', route('users::view', [
            'user' => 123,
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
    public function itFailsToViewUserDueToRecordNotFound(): void
    {
        $this->withoutMiddleware(Authenticate::class);

        $response = $this->json('GET', route('users::view', [
            'user' => 123,
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
    public function itSuccessfullyViewsUser(): void
    {
        $user = factory(User::class)->create();
        $user->assign(Role::ADMIN);

        $token = auth()->login($user);

        $response = $this->json('GET', route('users::view', [
            'user' => $user->id,
        ]), [], [
            'Content-Type'  => 'application/vnd.api+json',
            'Authorization' => \sprintf('Bearer %s', $token),
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
     *
     * @dataProvider viewDataProvider
     *
     * @param string $role
     * @param int    $status
     */
    public function itVerifiesRoleAccessPermissionsToViewUser(string $role, int $status): void
    {
        $user = factory(User::class)->create();
        $user->assign($role);

        $token = auth()->login($user);

        $response = $this->json('GET', route('users::view', [
            'user' => $user->id,
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
    public function viewDataProvider(): array
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
