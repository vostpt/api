<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\AuthController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use VOSTPT\Models\Role;
use VOSTPT\Models\User;
use VOSTPT\Tests\Integration\TestCase;

class AuthenticateEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itFailsToAuthenticateDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('POST', route('auth::authenticate'));

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
    public function itFailsToAuthenticateDueToValidation(): void
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
