<?php

declare(strict_types=1);

namespace VOSTPT\Jobs\Warnings;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use VOSTPT\Models\County;
use VOSTPT\ServiceClients\Contracts\IpmaServiceClient;

class WarningFetcher implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use SerializesModels;

    const ALLOWED_AWARENESS_LEVELS = [
        'yellow',
        'red',
    ];

    // This array will map the Ipma areaId code to County id on the DB (counties table).
    const AREA_CODES_MAP = [
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
        'LSB' => 153,
        'PTG' => 177,
        'PTO' => 190,
        'STM' => 212,
        'STB' => 229,
        'VCT' => 239,
        'VRL' => 254,
        'VIS' => 277,
        'MCN' => 288, // Madeira - Costa Norte (São Vicente)
        'MCS' => 281, // Madeira - Costa Sul (Funchal)
        'MRM' => 287, // Madeira - R. Montanhosas (Santana)
        'MPS' => 289, // Madeira - Porto Santo
        'AOR' => 293, // Açores - Grupo Oriental (Ponta Delgada)
        'ACE' => 300, // Açores - Grupo Central (Calheta de São Jorge)
        'AOC' => 307, // Açores - Grupo Ocidental (Santa Cruz das Flores)
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
     * Execute the job.
     *
     * @param IpmaServiceClient        $serviceClient
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     */
    public function handle(IpmaServiceClient $serviceClient, LoggerInterface $logger): bool
    {
        $this->serviceClient = $serviceClient;
        $this->logger        = $logger;

        $this->fetchIpmaWarnings();

        return true;
    }

    /**
     * Fetch Ipma warnings.
     *
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     */
    private function fetchIpmaWarnings(): bool
    {
        $this->logger->info('Fetching Ipma warnings...');

        $response = $this->serviceClient->getWarnings();

        $ipmaWarnings = collect($response['data'])
            ->filter(function ($value) {
                if (! empty($value['awarenessLevelID'])) {
                    $awarenessLevelId = \mb_strtolower($value['awarenessLevelID']);
                    $awarenessIsAllowed = \in_array($awarenessLevelId, self::ALLOWED_AWARENESS_LEVELS, true);
                    if ($awarenessIsAllowed) {
                        return $value;
                    }
                }
            })->map(function ($item) {
                // get aux data
                $countyId = Arr::get(self::AREA_CODES_MAP, $item['idAreaAviso']);
                $county = County::find($countyId);
                $district = $county->district;
                $region = ! \in_array($district->id, [19, 20], true) ? 'CONTINENTE' : $district->name;

                // set/modify attributtes
                $item['id'] = Uuid::uuid4()->toString();
                $item['region'] = $region;
                $item['county'] = $county->name;
                $item['started_at'] = $this->carbonise($item['startTime'])->toDateTimeString();
                $item['ended_at'] = $this->carbonise($item['endTime'])->toDateTimeString();

                return $item;
            });

        if (Cache::has('ipma_warnings')) {
            Cache::forget('ipma_warnings');
        }

        Cache::forever('ipma_warnings', $ipmaWarnings);

        $this->logger->info('...done!');

        return true;
    }

    /**
     * @param string|null $dateTime
     * @return Carbon|null
     */
    private function carbonise(string $dateTime = null): ?Carbon
    {
        if ($dateTime === null) {
            return null;
        }

        return Carbon::parse($dateTime);
    }
}
