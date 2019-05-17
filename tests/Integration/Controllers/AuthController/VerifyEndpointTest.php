<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\AuthController;

use VOSTPT\Models\Role;
use VOSTPT\Models\User;
use VOSTPT\Tests\Integration\RefreshDatabase;
use VOSTPT\Tests\Integration\TestCase;

class VerifyEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itFailsToVerifyAccessTokenDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('GET', route('auth::verify'));

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
    public function itFailsToVerifyAccessTokenDueToMissingAccessToken(): void
    {
        $response = $this->json('GET', route('auth::verify'), [], [
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
    public function itFailsToVerifyAccessTokenDueToExpiredAccessToken(): void
    {
        $user = factory(User::class)->create()->assign(Role::ADMINISTRATOR);

        // Create an access token with a TTL of one second
        $token = auth()->claims(['exp' => \time() + 1])->login($user);

        \sleep(1);

        $response = $this->json('GET', route('auth::verify'), [], [
            'Content-Type'  => 'application/vnd.api+json',
            'Authorization' => \sprintf('Bearer %s', $token),
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(401);
        $response->assertJson([
            'errors' => [
                [
                    'status' => 401,
                    'detail' => 'Token has expired',
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function itSuccessfullyVerifiesAccessToken(): void
    {
        $user = factory(User::class)->create()->assign(Role::ADMINISTRATOR);

        $token = auth()->login($user);

        $response = $this->json('GET', route('auth::verify'), [], [
            'Content-Type'  => 'application/vnd.api+json',
            'Authorization' => \sprintf('Bearer %s', $token),
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'meta' => [
                'expires_in',
            ],
        ]);
    }

    /**
     * @test
     * @dataProvider verifyDataProvider
     *
     * @param string $role
     * @param int    $status
     */
    public function itVerifiesRoleAccessPermissionsToVerifyAccessToken(string $role, int $status): void
    {
        $user = factory(User::class)->create()->assign($role);

        $token = auth()->login($user);

        $response = $this->json('GET', route('auth::verify'), [], [
            'Content-Type'  => 'application/vnd.api+json',
            'Authorization' => \sprintf('Bearer %s', $token),
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus($status);
    }

    /**
     * @return array
     */
    public function verifyDataProvider(): array
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
