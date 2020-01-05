<?php

declare(strict_types=1);

namespace VOSTPT\Jobs\Ipma;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Exception\HttpException;
use VOSTPT\Models\County;
use VOSTPT\Repositories\Contracts\CountyRepository;
use VOSTPT\ServiceClients\Contracts\IpmaApiServiceClient;

class WarningFetcher implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use SerializesModels;

    /**
     * Awareness levels.
     *
     * Yellow: Weather sensitive activities may be affected
     * Orange: Weather conditions involving moderate to high risk
     * Red:    Weather conditions involving high risk
     */
    private const ALLOWED_AWARENESS_LEVELS = [
        'yellow',
        'orange',
        'red',
    ];

    /**
     * Best effort IPMA Area to County code mapper.
     *
     * @see http://api.ipma.pt/open-data/distrits-islands.json
     */
    private const COUNTY_MAPPER = [
        'AVR' => '010500',
        'BJA' => '020500',
        'BRG' => '030300',
        'BGC' => '040200',
        'CBO' => '050200',
        'CBR' => '060300',
        'EVR' => '070500',
        'FAR' => '080500',
        'GDA' => '090700',
        'LRA' => '100900',
        'LSB' => '110600', // Mainland [Lisboa, Lisboa - Jardim Botânico]
        'PTG' => '121400',
        'PTO' => '131200',
        'STM' => '141600',
        'STB' => '151200',
        'VCT' => '160900',
        'VRL' => '171400',
        'VIS' => '182300',
        'MCN' => '311000', // Madeira - Costa Norte [São Vicente]
        'MCS' => '310300', // Madeira - Costa Sul [Funchal]
        'MRM' => '310900', // Madeira - R. Montanhosas [Santana]
        'MPS' => '320100', // Madeira - Porto Santo
        'AOR' => '420300', // Açores - Grupo Oriental [Ponta Delgada, Vila do Porto]
        'ACE' => '430100', // Açores - Grupo Central [Angra do Heroísmo, Santa Cruz da Graciosa, Velas, Madalena, Horta]
        'AOC' => '480200', // Açores - Grupo Ocidental [Santa Cruz das Flores, Vila do Corvo]
    ];

    /**
     * @var IpmaApiServiceClient
     */
    private $serviceClient;

    /**
     * @var CountyRepository
     */
    private $countyRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Cache implementation.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    private $cache;

    /**
     * @var array
     */
    private $counties = [];

    /**
     * Execute the job.
     *
     * @param IpmaApiServiceClient                   $serviceClient
     * @param CountyRepository                       $countyRepository
     * @param \Psr\Log\LoggerInterface               $logger
     * @param \Illuminate\Contracts\Cache\Repository $cache
     *
     * @return bool
     */
    public function handle(
        IpmaApiServiceClient $serviceClient,
        CountyRepository $countyRepository,
        LoggerInterface $logger,
        Cache $cache
    ): bool {
        $this->serviceClient    = $serviceClient;
        $this->countyRepository = $countyRepository;
        $this->logger           = $logger;
        $this->cache            = $cache;

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
            $results = $this->serviceClient->getWarnings();
        } catch (HttpException|ClientExceptionInterface $exception) {
            $this->logger->error($exception->getMessage());

            return false;
        }

        $warnings = collect($results)
            ->filter(static function (array $warning) {
                return \in_array($warning['awarenessLevelID'], self::ALLOWED_AWARENESS_LEVELS, true);
            })->map(function (array $warning) {
                return \array_merge($warning, [
                    'id'         => Uuid::uuid4()->toString(),
                    'county'     => $this->getCounty(self::COUNTY_MAPPER[$warning['idAreaAviso']]),
                    'started_at' => Carbon::parse($warning['startTime']),
                    'ended_at'   => Carbon::parse($warning['endTime']),
                ]);
            });

        $this->cache->forever('ipma_warnings', $warnings);

        $this->logger->info('...done!');

        return true;
    }

    /**
     * @param string $ipmaCountyId
     *
     * @return County
     */
    private function getCounty(string $ipmaCountyId): ?County
    {
        if (\array_key_exists($ipmaCountyId, $this->counties)) {
            return $this->counties[$ipmaCountyId];
        }

        if ($county = $this->countyRepository->findByCode($ipmaCountyId)) {
            $this->counties[$ipmaCountyId] = $county;

            return $county;
        }

        return null;
    }
}
