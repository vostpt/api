<?php

declare(strict_types=1);

namespace VOSTPT\Jobs\Ipma;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use VOSTPT\Models\WeatherObservation;
use VOSTPT\Models\WeatherStation;
use VOSTPT\Repositories\Contracts\WeatherObservationRepository;
use VOSTPT\Repositories\WeatherStationRepository;
use VOSTPT\ServiceClients\Contracts\IpmaApiServiceClient;

class SurfaceObservationFetcher implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use SerializesModels;

    /**
     * @var IpmaApiServiceClient
     */
    private $serviceClient;

    /**
     * @var WeatherStationRepository
     */
    private $weatherStationRepository;

    /**
     * @var WeatherObservationRepository
     */
    private $weatherObservationRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Station cache.
     *
     * @var array
     */
    private $stations = [];

    /**
     * Execute the job.
     *
     * @param IpmaApiServiceClient         $serviceClient
     * @param WeatherStationRepository     $weatherStationRepository
     * @param WeatherObservationRepository $weatherObservationRepository
     * @param \Psr\Log\LoggerInterface     $logger
     *
     * @return bool
     */
    public function handle(
        IpmaApiServiceClient $serviceClient,
        WeatherStationRepository $weatherStationRepository,
        WeatherObservationRepository $weatherObservationRepository,
        LoggerInterface $logger
    ): bool {
        $this->serviceClient                = $serviceClient;
        $this->weatherStationRepository     = $weatherStationRepository;
        $this->weatherObservationRepository = $weatherObservationRepository;
        $this->logger                       = $logger;

        $this->fetchSurfaceObservations();

        return true;
    }

    /**
     * Fetch IPMA warnings.
     *
     * @return bool
     */
    private function fetchSurfaceObservations(): bool
    {
        $this->logger->info('Fetching IPMA surface observations...');

        try {
            $results = $this->serviceClient->getSurfaceObservations();
        } catch (HttpException|ClientExceptionInterface $exception) {
            $this->logger->error($exception->getMessage());

            return false;
        }

        foreach ($results as $dateTime => $observations) {
            $timestamp = Carbon::parse($dateTime);

            foreach ($observations as $stationNumber => $observation) {
                // Skip stations without observations
                if (! \is_array($observation)) {
                    $this->logger->notice(\sprintf(
                        'No observation data @ %s for station number %d',
                        $timestamp->toDateTimeString(),
                        $stationNumber
                    ));

                    continue;
                }

                $this->storeWeatherObservation((string) $stationNumber, $timestamp, $observation);
            }
        }

        $this->logger->info('...done!');

        return true;
    }

    /**
     * @param string $stationNumber
     * @param Carbon $timestamp
     * @param array  $observation
     *
     * @return bool
     */
    private function storeWeatherObservation(string $stationNumber, Carbon $timestamp, array $observation): bool
    {
        if (! $station = $this->getWeatherStation($stationNumber)) {
            return false;
        }

        if ($this->weatherObservationRepository->findByStationAndTimestamp($station, $timestamp)) {
            $this->logger->notice(\sprintf(
                'Observation for %s @ %s already exists, skipping...',
                $stationNumber,
                $timestamp->toDateTimeString()
            ));

            return false;
        }

        $weatherObservation = new WeatherObservation();

        $weatherObservation->station()->associate($station);
        $weatherObservation->temperature          = $observation['temperatura'];
        $weatherObservation->humidity             = $observation['humidade'];
        $weatherObservation->wind_speed           = $observation['intensidadeVentoKM'];
        $weatherObservation->wind_direction       = WeatherObservation::WIND_DIRECTIONS[$observation['idDireccVento']];
        $weatherObservation->precipitation        = $observation['precAcumulada'];
        $weatherObservation->atmospheric_pressure = $observation['pressao'];
        $weatherObservation->radiation            = $observation['radiacao'];
        $weatherObservation->timestamp            = $timestamp;

        return $weatherObservation->save();
    }

    /**
     * @param string $serial
     *
     * @return WeatherStation
     */
    private function getWeatherStation(string $serial): ?WeatherStation
    {
        if (\array_key_exists($serial, $this->stations)) {
            return $this->stations[$serial];
        }

        if ($station = $this->weatherStationRepository->findByEntityAndSerial('IPMA', $serial)) {
            $this->stations[$serial] = $station;

            return $station;
        }

        return null;
    }
}
