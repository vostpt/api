<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\AcronymController;

use Tymon\JWTAuth\Http\Middleware\Authenticate;
use VOSTPT\Models\Acronym;
use VOSTPT\Models\Role;
use VOSTPT\Models\User;
use VOSTPT\Tests\Integration\RefreshDatabase;
use VOSTPT\Tests\Integration\TestCase;

class DeleteEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itFailsToDeleteAcronymDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('DELETE', route('acronyms::delete', [
            'Acronym' => 1,
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
    public function itFailsToDeleteAcronymDueToMissingJwtToken(): void
    {
        $response = $this->json('DELETE', route('acronyms::delete', [
            'Acronym' => 1,
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
    public function itFailsToDeleteAcronymDueToRecordNotFound(): void
    {
        $this->withoutMiddleware(Authenticate::class);

        $response = $this->json('DELETE', route('acronyms::delete', [
            'Acronym' => 1,
        ]), [], [
            'Content-Type' => 'application/vnd.api+json',
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(404);
        $response->assertJson([
            'errors' => [
                [
                    'status' => 404,
                    'detail' => 'Acronym Not Found',
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function itSuccessfullyDeletesAcronym(): void
    {
        $user = factory(User::class)->create()->assign(Role::ADMINISTRATOR);

        $acronym = factory(Acronym::class)->create([
            'initials' => 'PSP',
            'meaning'  => 'Polícia de Segurança Pública',
        ]);

        $this->assertDatabaseHas('acronyms', [
            'initials' => 'PSP',
            'meaning'  => 'Polícia de Segurança Pública',
        ]);

        $token = auth()->login($user);

        $response = $this->json('DELETE', route('acronyms::delete', [
            'Acronym' => $acronym->getKey(),
        ]), [], [
            'Content-Type'  => 'application/vnd.api+json',
            'Authorization' => \sprintf('Bearer %s', $token),
        ]);

        $this->assertDatabaseMissing('acronyms', [
            'initials' => 'PSP',
            'meaning'  => 'Polícia de Segurança Pública',
        ]);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'type',
                'id',
                'attributes' => [
                    'initials',
                    'meaning',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
    }

    /**
     * @test
     * @dataProvider deleteDataProvider
     *
     * @param string $role
     * @param int    $status
     */
    public function itVerifiesRoleAccessPermissionsToDeleteAcronym(string $role, int $status): void
    {
        $user = factory(User::class)->create()->assign($role);

        $acronym = factory(Acronym::class)->create();

        $token = auth()->login($user);

        $response = $this->json('DELETE', route('acronyms::delete', [
            'Acronym' => $acronym->getKey(),
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
    public function deleteDataProvider(): array
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
