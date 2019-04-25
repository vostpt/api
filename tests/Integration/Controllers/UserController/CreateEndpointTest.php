<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\UserController;

use VOSTPT\Tests\Integration\RefreshDatabase;
use VOSTPT\Tests\Integration\TestCase;

class CreateEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itFailsToCreateUserDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('POST', route('users::create'));

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
    public function itFailsToCreateUserDueToValidation(): void
    {
        $response = $this->json('POST', route('users::create'), [
            'name'                  => \str_repeat('name', 70),
            'surname'               => \str_repeat('surname', 40),
            'email'                 => 'invalid at email dot tld',
            'password'              => 'secret',
            'password_confirmation' => 'code',
        ], [
            'Content-Type' => 'application/vnd.api+json',
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
    public function itSuccessfullyCreatesUser(): void
    {
        $this->assertDatabaseMissing('users', [
            'name'    => 'Fernando',
            'surname' => 'Pessoa',
            'email'   => 'fernando.pessoa@vost.pt',
        ]);
        $response = $this->json('POST', route('users::create'), [
            'name'                  => 'Fernando',
            'surname'               => 'Pessoa',
            'email'                 => 'fernando.pessoa@vost.pt',
            'password'              => 'absinto',
            'password_confirmation' => 'absinto',
        ], [
            'Content-Type' => 'application/vnd.api+json',
        ]);

        $this->assertDatabaseHas('users', [
            'name'    => 'Fernando',
            'surname' => 'Pessoa',
            'email'   => 'fernando.pessoa@vost.pt',
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(201);
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
}
