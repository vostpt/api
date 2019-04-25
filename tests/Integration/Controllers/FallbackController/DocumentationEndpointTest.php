<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\FallbackController;

use Illuminate\Support\Str;
use VOSTPT\Tests\Integration\TestCase;

class DocumentationEndpointTest extends TestCase
{
    /**
     * @test
     */
    public function itSuccessfullyRedirectsToTheDocumentation(): void
    {
        $response = $this->json('GET', Str::random());

        $response->assertStatus(302);
        $response->assertRedirect('/documentation');
    }
}
