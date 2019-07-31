<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\AuthController;

use VOSTPT\Models\Role;
use VOSTPT\Models\User;
use VOSTPT\Tests\Integration\RefreshDatabase;
use VOSTPT\Tests\Integration\TestCase;

class RefreshEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itFailsToRefreshAccessTokenDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('GET', route('auth::refresh'), [], [
            'Content-Type' => 'application/vnd.api+json;charset=utf-8',
        ]);

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
    public function itFailsToRefreshAccessTokenDueToInvalidAcceptHeader(): void
    {
        $response = $this->json('GET', route('auth::refresh'), [], [
            'Accept' => 'application/vnd.api+json;charset=utf-8',
        ]);

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
    public function itFailsToRefreshAccessTokenDueToMissingAccessToken(): void
    {
        $response = $this->json('GET', route('auth::refresh'), [], [
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
    public function itSuccessfullyRefreshesAccessToken(): void
    {
        $user = factory(User::class)->create()->assign(Role::ADMINISTRATOR);

        $token = auth()->login($user);

        $response = $this->json('GET', route('auth::refresh'), [], [
            'Content-Type'  => 'application/vnd.api+json',
            'Authorization' => \sprintf('Bearer %s', $token),
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertHeader('Cache-Control');
        $response->assertHeader('Pragma', 'no-cache');
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'meta' => [
                'access_token',
                'token_type',
                'expires_in',
            ],
        ]);
    }

    /**
     * @test
     * @dataProvider refreshDataProvider
     *
     * @param string $role
     * @param int    $status
     */
    public function itVerifiesRoleAccessPermissionsToRefreshAccessToken(string $role, int $status): void
    {
        $user = factory(User::class)->create()->assign($role);

        $token = auth()->login($user);

        $response = $this->json('GET', route('auth::refresh'), [], [
            'Content-Type'  => 'application/vnd.api+json',
            'Authorization' => \sprintf('Bearer %s', $token),
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus($status);
    }

    /**
     * @return array
     */
    public function refreshDataProvider(): array
    {
        return [
            'Administrator' => [
                Role::ADMINISTRATOR,
                201,
            ],
            'Moderator' => [
                Role::MODERATOR,
                201,
            ],
            'Contributor' => [
                Role::CONTRIBUTOR,
                201,
            ],
        ];
    }
}
