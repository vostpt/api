<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\AuthController;

use VOSTPT\Models\Role;
use VOSTPT\Models\User;
use VOSTPT\Tests\Integration\RefreshDatabase;
use VOSTPT\Tests\Integration\TestCase;

class AuthenticateEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itFailsToAuthenticateDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('POST', route('auth::authenticate'), [], [
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
    public function itFailsToAuthenticateDueToInvalidAcceptHeader(): void
    {
        $response = $this->json('POST', route('auth::authenticate'), [], [
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
    public function itFailsToAuthenticateDueToInvalidInput(): void
    {
        $response = $this->json('POST', route('auth::authenticate'), [
            'email' => 'invalid at email dot tld',
        ], [
            'Content-Type' => 'application/vnd.api+json',
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                [
                    'detail' => 'The email must be a valid email address.',
                    'meta'   => [
                        'field' => 'email',
                    ],
                ],
                [
                    'detail' => 'The password field is required.',
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
    public function itFailsToAuthenticateDueToInvalidCredentials(): void
    {
        $response = $this->json('POST', route('auth::authenticate'), [
            'email'    => 'fernando.pessoa@vost.pt',
            'password' => 'absinto',
        ], [
            'Content-Type' => 'application/vnd.api+json',
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(401);
        $response->assertJson([
            'errors' => [
                [
                    'status' => 401,
                    'detail' => 'Invalid credentials',
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function itSuccessfullyAuthenticates(): void
    {
        factory(User::class)->create([
            'email'    => 'fernando.pessoa@vost.pt',
            'password' => 'absinto',
        ])->assign(Role::ADMINISTRATOR);

        $response = $this->json('POST', route('auth::authenticate'), [
            'email'    => 'fernando.pessoa@vost.pt',
            'password' => 'absinto',
        ], [
            'Content-Type' => 'application/vnd.api+json',
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
}
