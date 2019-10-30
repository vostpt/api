<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\UserController;

use VOSTPT\Models\Role;
use VOSTPT\Models\User;
use VOSTPT\Tests\Integration\RefreshDatabaseWithRoles;
use VOSTPT\Tests\Integration\TestCase;

class IndexEndpointTest extends TestCase
{
    use RefreshDatabaseWithRoles;

    /**
     * @test
     */
    public function itFailsToIndexUsersDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('GET', route('users::index'), [], static::INVALID_CONTENT_TYPE_HEADER);

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
    public function itFailsToIndexUsersDueToInvalidAcceptHeader(): void
    {
        $response = $this->json('GET', route('users::index'), [], static::INVALID_ACCEPT_HEADER);

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
    public function itFailsToIndexUsersDueToMissingAccessToken(): void
    {
        $response = $this->json('GET', route('users::index'), [], static::VALID_CONTENT_TYPE_HEADER);

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
    public function itFailsToIndexUsersDueToInvalidInput(): void
    {
        $user = factory(User::class)->create()->assign(Role::ADMINISTRATOR);

        $token = auth()->login($user);

        $response = $this->json('GET', route('users::index'), [
            'page' => [
                'number' => 'second',
                'size'   => 'ten',
            ],
            'ids' => [
                123,
            ],
            'search' => '',
            'exact'  => 'yes',
            'sort'   => 'id',
            'order'  => 'up',
            'roles'  => [
                1024,
            ],
        ], [
            'Content-Type'  => 'application/vnd.api+json',
            'Authorization' => \sprintf('Bearer %s', $token),
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                [
                    'detail' => 'The page.number must be an integer.',
                    'meta'   => [
                        'field' => 'page.number',
                    ],
                ],
                [
                    'detail' => 'The page.size must be an integer.',
                    'meta'   => [
                        'field' => 'page.size',
                    ],
                ],
                [
                    'detail' => 'The search must be a string.',
                    'meta'   => [
                        'field' => 'search',
                    ],
                ],
                [
                    'detail' => 'The exact field must be true or false.',
                    'meta'   => [
                        'field' => 'exact',
                    ],
                ],
                [
                    'detail' => 'The selected sort is invalid.',
                    'meta'   => [
                        'field' => 'sort',
                    ],
                ],
                [
                    'detail' => 'The selected order is invalid.',
                    'meta'   => [
                        'field' => 'order',
                    ],
                ],
                [
                    'detail' => 'The selected ids.0 is invalid.',
                    'meta'   => [
                        'field' => 'ids.0',
                    ],
                ],
                [
                    'detail' => 'The roles.0 value must be one of: administrator, contributor, moderator',
                    'meta'   => [
                        'field' => 'roles.0',
                    ],
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function itSuccessfullyIndexesUsers(): void
    {
        $user = factory(User::class)->create()->assign(Role::ADMINISTRATOR);

        $token = auth()->login($user);

        $ids = factory(User::class, 20)->create()->each(static function (User $user): void {
            $user->assign([
                Role::MODERATOR,
                Role::CONTRIBUTOR,
            ]);
        })->pluck('id')->all();

        $response = $this->json('GET', route('users::index'), [
            'page' => [
                'number' => 2,
                'size'   => 5,
            ],
            'ids'    => $ids,
            'search' => 'a b c d e f 0 1 2 3 4 5 6 7 8 9',
            'sort'   => 'surname',
            'order'  => 'asc',
            'roles'  => [
                Role::MODERATOR,
                Role::CONTRIBUTOR,
            ],
        ], [
            'Content-Type'  => 'application/vnd.api+json',
            'Authorization' => \sprintf('Bearer %s', $token),
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'links' => [
                'first',
                'last',
                'prev',
                'next',
            ],
            'data' => [
                '*' => [
                    'type',
                    'id',
                    'attributes' => [
                        'email',
                        'name',
                        'surname',
                        'created_at',
                        'updated_at',
                    ],
                    'links' => [
                        'self',
                    ],
                ],
            ],
            'meta' => [
                'items',
                'total',
            ],
        ]);
    }

    /**
     * @test
     * @dataProvider indexDataProvider
     *
     * @param string $role
     * @param int    $status
     */
    public function itVerifiesRoleAccessPermissionsToIndexUsers(string $role, int $status): void
    {
        $user = factory(User::class)->create()->assign($role);

        $token = auth()->login($user);

        $response = $this->json('GET', route('users::index'), [], [
            'Content-Type'  => 'application/vnd.api+json',
            'Authorization' => \sprintf('Bearer %s', $token),
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus($status);
    }

    /**
     * @return array
     */
    public function indexDataProvider(): array
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
