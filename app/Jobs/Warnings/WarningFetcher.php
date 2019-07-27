<?php

declare(strict_types=1);

namespace VOSTPT\Jobs\Warnings;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use VOSTPT\Models\IpmaWarning;
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

        foreach ($response['data'] as $data) {
            $awarenessLevelId   = \mb_strtolower($data['awarenessLevelID']);
            $awarenessIsAllowed = \in_array($awarenessLevelId, self::ALLOWED_AWARENESS_LEVELS, true);
            if (empty($awarenessLevelId) || ! $awarenessIsAllowed) {
                continue;
            }

            DB::transaction(function () use ($data, $awarenessLevelId) {
                $startedAt = $this->carbonise($data['startTime'])->toDateTimeString();
                $endedAt = $this->carbonise($data['endTime'])->toDateTimeString();

                $warningExists = IpmaWarning::query()
                    ->where('area_id', $data['idAreaAviso'])
                    ->where('awareness_level_id', $awarenessLevelId)
                    ->where('is_active', 1)
                    ->where('started_at', '<=', $startedAt)
                    ->where('ended_at', '>', $startedAt)
                    ->where('started_at', '<', $endedAt)
                    ->where('ended_at', '>=', $endedAt)
                    ->first();

                if (! $warningExists) {
                    $areasMap = config('ipma.areas', []);

                    $warning = new IpmaWarning();
                    $warning->uuid = Uuid::uuid4();
                    $warning->text = ! empty($data['text']) ? $data['text'] : null;
                    $warning->awareness_type_name = $data['awarenessTypeName'];
                    $warning->zone_name = Arr::get($areasMap, $data['idAreaAviso'].'.zone');
                    $warning->area_id = $data['idAreaAviso'];
                    $warning->area_name = Arr::get($areasMap, $data['idAreaAviso'].'.area');
                    $warning->awareness_level_id = $awarenessLevelId;
                    $warning->started_at = $startedAt;
                    $warning->ended_at = $endedAt;
                    $warning->save();

                    $this->logger->info(\sprintf(
                        'Created Ipma warning %s',
                        $warning->id
                    ));
                }
            });
        }
        $this->logger->info('...done!');

        return true;
    }

    /**
     * Create a Carbon object from a date string.
     *
     * @param string $dateTime
     *
     * @return Carbon
     * @throws \RuntimeException
     *
     */
    private function carbonise(string $dateTime = null): ?Carbon
    {
        if ($dateTime === null) {
            return null;
        }

        return Carbon::parse($dateTime);
    }
}
