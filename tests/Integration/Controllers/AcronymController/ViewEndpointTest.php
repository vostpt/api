<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\AcronymController;

use VOSTPT\Models\Acronym;
use VOSTPT\Tests\Integration\RefreshDatabase;
use VOSTPT\Tests\Integration\TestCase;

class ViewEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itFailsToViewAcronymDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('GET', route('acronyms::view', [
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
    public function itFailsToViewAcronymDueToRecordNotFound(): void
    {
        $response = $this->json('GET', route('acronyms::view', [
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
    public function itSuccessfullyViewsAcronym(): void
    {
        $acronym = factory(Acronym::class)->create();

        $response = $this->json('GET', route('acronyms::view', [
            'Acronym' => $acronym->getKey(),
        ]), [], [
            'Content-Type' => 'application/vnd.api+json',
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
}
