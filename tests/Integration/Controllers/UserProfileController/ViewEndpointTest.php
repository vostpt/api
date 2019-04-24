<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\UserProfileController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use VOSTPT\Models\Role;
use VOSTPT\Models\User;
use VOSTPT\Tests\Integration\TestCase;

class ViewEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itFailsToViewProfileDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('GET', route('users::profile::view'));

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
    public function itFailsToViewProfileDueToMissingJwtToken(): void
    {
        $response = $this->json('GET', route('users::profile::view'), [], [
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
    public function itSuccessfullyViewsProfile(): void
    {
        $user = factory(User::class)->create()->assign(Role::ADMINISTRATOR);

        $token = auth()->login($user);

        $response = $this->json('GET', route('users::profile::view'), [], [
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
    public function itVerifiesRoleAccessPermissionsToViewProfile(string $role, int $status): void
    {
        $user = factory(User::class)->create()->assign($role);

        $token = auth()->login($user);

        $response = $this->json('GET', route('users::profile::view'), [], [
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
