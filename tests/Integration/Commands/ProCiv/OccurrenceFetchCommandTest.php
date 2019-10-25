<?php

declare(strict_types=1);

namespace VOSTPT\Tests\Integration\Commands\ProCiv;

use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use VOSTPT\Models\Occurrence;
use VOSTPT\Models\OccurrenceStatus;
use VOSTPT\Models\OccurrenceType;
use VOSTPT\Models\Parish;
use VOSTPT\Models\ProCivOccurrence;
use VOSTPT\Tests\Integration\HttpClientMocker;
use VOSTPT\Tests\Integration\TestCase;

class OccurrenceFetchCommandTest extends TestCase
{
    use HttpClientMocker;
    use RefreshDatabase;

    /**
     * @test
     */
    public function itSuccessfullyFetchesOccurrences(): void
    {
        $response1 = $this->createHttpResponse('tests/data/ProCiv/OccurrencesByLocation.json');
        $response2 = $this->createHttpResponse('tests/data/ProCiv/MainOccurrences.json');

        $this->app->instance(Client::class, $this->createHttpClient($response1, $response2));

        $this->app->instance(LoggerInterface::class, new NullLogger());

        // Despacho de 1º Alerta
        factory(OccurrenceStatus::class)->create([
            'code' => 4,
        ]);

        // Patrulhamento, Reconhecimento e Vigilância
        factory(OccurrenceType::class)->create([
            'code' => 4301,
        ]);

        // Produtos
        factory(OccurrenceType::class)->create([
            'code' => 2203,
        ]);

        // Pópulo e Ribalonga
        factory(Parish::class)->create([
            'code' => '170122',
        ]);

        // Amieira e Alqueva
        factory(Parish::class)->create([
            'code' => '070909',
        ]);

        $existingOccurrence = factory(Occurrence::class)->make();

        factory(ProCivOccurrence::class)->create([
            'remote_id' => '2019070021974',
        ])->parent()->save($existingOccurrence);

        $this->assertDatabaseMissing('prociv_occurrences', [
            'remote_id' => '2019170027060',
        ]);

        $this->artisan('prociv:fetch:occurrences');

        $this->assertDatabaseHas('prociv_occurrences', [
            'remote_id' => '2019170027060',
        ]);

        $this->assertDatabaseHas('prociv_occurrence_logs', [
            'reinforcement_groups' => 'EAUF 02 FEPC, GRIF 01 e GRIF 02 FEPC, GRIF 01  Coimbra, GRIF 01 Vila Real, CATA Viseu.',
        ]);
    }
}
