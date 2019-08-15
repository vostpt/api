<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Controllers\OccurrenceReportController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Psr\Log\NullLogger;
use VOSTPT\Filters\OccurrenceFilter;
use VOSTPT\Models\Occurrence;
use VOSTPT\Models\ProCivOccurrence;
use VOSTPT\Reports\OccurrenceReport;
use VOSTPT\Repositories\OccurrenceRepository;
use VOSTPT\Tests\Integration\TestCase;

class DownloadReportEndpointTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itFailsToDownloadOccurrenceReportDueToInvalidContentTypeHeader(): void
    {
        $response = $this->json('GET', route('occurrences::reports::download', [
            'signature' => \sha1('test'),
        ]), [], static::INVALID_CONTENT_TYPE_HEADER);

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
    public function itFailsToDownloadOccurrenceReportDueToInvalidAcceptHeader(): void
    {
        $response = $this->json('GET', route('occurrences::reports::download', [
            'signature' => \sha1('test'),
        ]), [], static::INVALID_ACCEPT_HEADER);

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
    public function itFailsToDownloadOccurrenceReportDueToRecordNotFound(): void
    {
        $response = $this->json('GET', route('occurrences::reports::download', [
            'signature' => \sha1('test'),
        ]), [], static::VALID_CONTENT_TYPE_HEADER);

        $response->assertHeader('Content-Type', 'application/vnd.api+json');
        $response->assertStatus(404);
        $response->assertJson([
            'errors' => [
                [
                    'status' => 404,
                    'detail' => 'Occurrence Report Not Found',
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function itSuccessfullyDownloadsOccurrenceReport(): void
    {
        $occurrences = factory(Occurrence::class, 20)->make();

        factory(ProCivOccurrence::class, 20)->create()->each(function (ProCivOccurrence $proCivOccurrence, $index) use ($occurrences) {
            $proCivOccurrence->parent()->save($occurrences[$index]);
        });

        $report = new OccurrenceReport(new OccurrenceRepository(), new OccurrenceFilter(), Storage::disk('local'));
        $report->generate(new NullLogger());

        $response = $this->json('GET', route('occurrences::reports::download', [
            'signature' => $report->getSignature(),
        ]), [], static::VALID_CONTENT_TYPE_HEADER);

        $response->assertHeader('Content-Type', 'application/zip');
        $response->assertStatus(200);
    }
}
