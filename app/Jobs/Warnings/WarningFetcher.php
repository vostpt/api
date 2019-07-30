<?php

declare(strict_types=1);

namespace VOSTPT\Jobs\Warnings;

use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use VOSTPT\Models\County;
use VOSTPT\ServiceClients\Contracts\IpmaServiceClient;

class WarningFetcher implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use SerializesModels;

    private const ALLOWED_AWARENESS_LEVELS = [
        'yellow',
        'red',
    ];

    /**
     * Best effort IPMA Area to County mapper
     * @see http://api.ipma.pt/open-data/distrits-islands.json
     */
    private const COUNTY_MAPPER = [
        'AVR' => 5,
        'BJA' => 24,
        'BRG' => 36,
        'BGC' => 49,
        'CBO' => 61,
        'CBR' => 73,
        'EVR' => 92,
        'FAR' => 106,
        'GDA' => 124,
        'LRA' => 140,
        'LSB' => 153, // Continente [Lisboa, Lisboa - Jardim Botânico]
        'PTG' => 177,
        'PTO' => 190,
        'STM' => 212,
        'STB' => 229,
        'VCT' => 239,
        'VRL' => 254,
        'VIS' => 277,
        'MCN' => 288, // Madeira - Costa Norte [São Vicente]
        'MCS' => 281, // Madeira - Costa Sul [Funchal]
        'MRM' => 287, // Madeira - R. Montanhosas [Santana)]
        'MPS' => 289, // Madeira - Porto Santo
        'AOR' => 293, // Açores - Grupo Oriental [Ponta Delgada, Vila do Porto]
        'ACE' => 297, // Açores - Grupo Central [Angra do Heroísmo, Santa Cruz da Graciosa, Velas, Madalena, Horta]
        'AOC' => 307, // Açores - Grupo Ocidental [Santa Cruz das Flores, Vila do Corvo]
    ];

    /**
     * @var IpmaServiceClient
     */
    private $serviceClient;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Cache implementation.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * Execute the job.
     *
     * @param IpmaServiceClient                      $serviceClient
     * @param \Psr\Log\LoggerInterface               $logger
     * @param \Illuminate\Contracts\Cache\Repository $cache
     *
     * @return bool
     */
    public function handle(IpmaServiceClient $serviceClient, LoggerInterface $logger, Cache $cache): bool
    {
        $this->serviceClient = $serviceClient;
        $this->logger        = $logger;
        $this->cache         = $cache;

        $this->fetchIpmaWarnings();

        return true;
    }

    /**
     * Fetch IPMA warnings.
     *
     * @return bool
     */
    private function fetchIpmaWarnings(): bool
    {
        $this->logger->info('Fetching IPMA warnings...');

        try {
            $response = $this->serviceClient->getWarnings();
        } catch (GuzzleException $exception) {
            $this->logger->error($exception->getMessage());

            return false;
        }

        $warnings = collect($response['data'])
            ->filter(function (array $warning) {
                return \in_array($warning['awarenessLevelID'], self::ALLOWED_AWARENESS_LEVELS, true);
            })->map(function (array $warning) {
                return \array_merge($warning, [
                    'id'         => Uuid::uuid4()->toString(),
                    'county'     => County::find(self::COUNTY_MAPPER[$warning['idAreaAviso']]),
                    'started_at' => Carbon::parse($warning['startTime']),
                    'ended_at'   => Carbon::parse($warning['endTime']),
                ]);
            });

        $this->cache->forever('ipma_warnings', $warnings);

        $this->logger->info('...done!');

        return true;
    }
}
