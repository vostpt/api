<?php

declare(strict_types=1);

namespace VOSTPT\Jobs\Occurrences;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Psr\Log\LoggerInterface;
use RuntimeException;
use VOSTPT\Models\Occurrence;
use VOSTPT\Models\Parish;
use VOSTPT\Models\ProCivOccurrence;
use VOSTPT\Models\ProCivOccurrenceLog;
use VOSTPT\Models\ProCivOccurrenceStatus;
use VOSTPT\Models\ProCivOccurrenceType;
use VOSTPT\ServiceClients\Contracts\ProCivServiceClient;

class ProCivOccurrenceFetcher implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use SerializesModels;

    /**
     * @var ProCivServiceClient
     */
    private $serviceClient;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Execute the job.
     *
     * @param ProCivServiceClient      $serviceClient
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return bool
     */
    public function handle(ProCivServiceClient $serviceClient, LoggerInterface $logger): bool
    {
        $this->serviceClient = $serviceClient;
        $this->logger        = $logger;

        $this->fetchOccurrences();

        $this->fetchOccurrenceSpecifics();

        $this->closeStalledOccurrences();

        return true;
    }

    /**
     * Fetch ProCiv occurrences.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return bool
     */
    private function fetchOccurrences(): bool
    {
        $this->logger->info('Fetching ProCiv occurrences...');

        $response = $this->serviceClient->getOccurrenceHistory();

        foreach ($response['Data'] as $data) {
            DB::transaction(function () use ($data) {
                if ($proCivOccurrence = ProCivOccurrence::where('remote_id', $data['Numero'])->first()) {
                    $occurrence = $proCivOccurrence->occurrence;
                } else {
                    $proCivOccurrence = new ProCivOccurrence();

                    $proCivOccurrence->remote_id = $data['Numero'];

                    $occurrence = new Occurrence();
                }

                $status = ProCivOccurrenceStatus::where('code', $data['EstadoOcorrenciaID'])->first();
                $proCivOccurrence->status()->associate($status);

                $type = ProCivOccurrenceType::where('code', $data['Natureza']['Codigo'])->first();
                $proCivOccurrence->type()->associate($type);

                $parish = Parish::where('code', $data['Freguesia']['DICOFRE'])->first();
                $occurrence->parish()->associate($parish);
                $occurrence->locality = $data['Localidade'];

                $occurrence->latitude = $data['Latitude'];
                $occurrence->longitude = $data['Longitude'];

                $occurrence->started_at = $this->carbonise($data['DataOcorrencia']);
                $occurrence->ended_at = $this->carbonise($data['DataFechoOperacional']);

                $proCivOccurrence->ground_assets_involved = $data['NumeroMeiosTerrestresEnvolvidos'];
                $proCivOccurrence->ground_operatives_involved = $data['NumeroOperacionaisTerrestresEnvolvidos'];

                $proCivOccurrence->aerial_assets_involved = $data['NumeroMeiosAereosEnvolvidos'];
                $proCivOccurrence->aerial_operatives_involved = $data['NumeroOperacionaisAereosEnvolvidos'];

                $proCivOccurrence->save();
                $proCivOccurrence->occurrence()->save($occurrence);

                // Exclude unaffected records from the logs
                if ($proCivOccurrence->wasRecentlyCreated || $proCivOccurrence->wasChanged()) {
                    $this->logger->info(\sprintf(
                        '%s ProCiv occurrence %s',
                        $proCivOccurrence->wasRecentlyCreated ? 'Created' : 'Updated',
                        $data['Numero']
                    ));
                }
            });
        }

        $this->logger->info('...done!');

        return true;
    }

    /**
     * Fetch ProCiv occurrence specifics.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return bool
     */
    private function fetchOccurrenceSpecifics(): bool
    {
        $this->logger->info('Fetching ProCiv occurrence specifics...');

        $response = $this->serviceClient->getMainOccurrences();

        foreach ($response['Data'] as $data) {
            DB::transaction(function () use ($data) {
                $proCivOccurrence = ProCivOccurrence::where('remote_id', $data['Numero'])->first();

                $this->logger->info(\sprintf('Storing ProCiv occurrence log for %s', $data['Numero']));

                $proCivOccurrenceLog = new ProCivOccurrenceLog();

                $proCivOccurrenceLog->rescue_operations_commander = $data['COS'];

                $proCivOccurrenceLog->entities_at_the_theatre_of_operations = $data['EntidadesNoTO'];

                $proCivOccurrenceLog->notes = $data['Notas'];

                $proCivOccurrenceLog->operational_command_post = $data['PCO'];

                $proCivOccurrenceLog->medium_aircrafts_involved = $data['NumAvioesMediosEnvolvidos'];
                $proCivOccurrenceLog->heavy_aircrafts_involved = $data['NumAvioesPesadosEnvolvidos'];
                $proCivOccurrenceLog->other_aircrafts_involved = $data['NumAvioesOutrosEnvolvidos'];

                $proCivOccurrenceLog->medium_helicopters_involved = $data['NumHelicopterosLigeirosMediosEnvolvidos'];
                $proCivOccurrenceLog->heavy_helicopters_involved = $data['NumHelicopterosPesadosEnvolvidos'];
                $proCivOccurrenceLog->other_helicopters_involved = $data['NumHelicopterosOutrosEnvolvidos'];

                $proCivOccurrenceLog->fire_fighter_assets_involved = $data['NumBombeirosEnvolvidos'];
                $proCivOccurrenceLog->fire_fighter_operatives_involved = $data['NumBombeirosOperEnvolvidos'];

                $proCivOccurrenceLog->special_fire_fighter_force_assets_involved = $data['NumFebEnvolvidos'];
                $proCivOccurrenceLog->special_fire_fighter_force_operatives_involved = $data['NumFebOperEnvolvidos'];

                $proCivOccurrenceLog->forest_sapper_assets_involved = $data['NumEsfEnvolvidos'];
                $proCivOccurrenceLog->forest_sapper_operatives_involved = $data['NumEsfOperEnvolvidos'];

                $proCivOccurrenceLog->armed_force_assets_involved = $data['NumFAAEnvolvidos'];
                $proCivOccurrenceLog->armed_force_operatives_involved = $data['NumFAAOperEnvolvidos'];

                $proCivOccurrenceLog->gips_assets_involved = $data['NumGNRGipsEnvolvidos'];
                $proCivOccurrenceLog->gips_operatives_involved = $data['NumGNRGipsOperEnvolvidos'];

                $proCivOccurrenceLog->gnr_assets_involved = $data['NumGNROutrosEnvolvidos'];
                $proCivOccurrenceLog->gnr_operatives_involved = $data['NumGNROutrosOperEnvolvidos'];

                $proCivOccurrenceLog->psp_assets_involved = $data['NumPSPEnvolvidos'];
                $proCivOccurrenceLog->psp_operatives_involved = $data['NumPSPOperEnvolvidos'];

                $proCivOccurrenceLog->reinforcement_groups_involved = $data['GruposReforcoEnvolvidos'];

                $proCivOccurrenceLog->other_operatives_involved = $data['OutrosOperacionaisEnvolvidos'];

                $proCivOccurrenceLog->state_of_affairs = $data['PontoSituacao'];
                $proCivOccurrenceLog->state_of_affairs_description = $data['POSITDescricao'];

                $proCivOccurrenceLog->active_previous_intervention_plan = $data['PPIAtivados'];

                $proCivOccurrence->logs()->attach($proCivOccurrenceLog);
            });
        }

        $this->logger->info('...done!');

        return true;
    }

    /**
     * Close stalled ProCiv occurrences.
     *
     * @return bool
     */
    private function closeStalledOccurrences(): bool
    {
        $this->logger->info('Closing stalled ProCiv occurrences...');

        $closedByVostStatus = ProCivOccurrenceStatus::where('code', ProCivOccurrenceStatus::CLOSED_BY_VOST)->first();

        foreach (ProCivOccurrence::stalled()->get() as $stalledProCivOccurrence) {
            $stalledProCivOccurrence->status()->associate($closedByVostStatus);
            $stalledProCivOccurrence->save();

            $this->logger->info(\sprintf('ProCiv occurrence %s closed', $stalledProCivOccurrence->remote_id));
        }

        $this->logger->info('...done!');

        return true;
    }

    /**
     * Create a Carbon object from a date string.
     *
     * @param string $dateTime
     *
     * @throws \RuntimeException
     *
     * @return Carbon
     */
    private function carbonise(string $dateTime = null): ?Carbon
    {
        if ($dateTime === null) {
            return null;
        }

        $matches = [];

        // Extract the Unix timestamp and timezone from a string like
        // /Date(1555770180000+0100)/
        //       ^--------^   ^-^^^
        if (\preg_match('/\/Date\((?P<timestamp>\d+)000(?P<tzh>\+\d{2})(?P<tzm>\d{2})\)\//', $dateTime, $matches) !== 1) {
            throw new RuntimeException('Unable to parse timestamp/timezone');
        }

        return Carbon::createFromTimestamp($matches['timestamp'], $matches['tzh'].':'.$matches['tzm']);
    }
}
