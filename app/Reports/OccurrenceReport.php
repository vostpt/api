<?php

declare(strict_types=1);

namespace VOSTPT\Reports;

use Generator;
use Illuminate\Database\Query\JoinClause;

class OccurrenceReport extends Report
{
    /**
     * {@inheritDoc}
     */
    public function getHeader(): array
    {
        return \array_merge([
            'ID',
            'Event ID',
            'Type',
            'Status',
            'Parish',
            'Locality',
            'Source',
            'Latitude',
            'Longitude',
            'Started',
            'Ended',
            'Created',
            'Updated',

            // ProCiv
            'ProCiv ID',
            'ProCiv Ground Assets',
            'ProCiv Ground Operatives',
            'ProCiv Aerial Assets',
            'ProCiv Aerial Operatives',

            // ProCiv Extra
            'ProCiv Rescue Operation Commander',
            'ProCiv Entities At The Theatre Of Operations',
            'ProCiv Notes',
            'ProCiv Operational Command Post',
            'ProCiv Medium Aircrafts',
            'ProCiv Heavy Aircrafts',
            'ProCiv Other Aircrafts',
            'ProCiv Medium Helicopters',
            'ProCiv Heavy Helicopters',
            'ProCiv Other Helicopters',
            'ProCiv Fire Fighter Assets',
            'ProCiv Fire Fighter Operatives',
            'ProCiv Special Fire Fighter Force Assets',
            'ProCiv Special Fire Fighter Force Operatives',
            'ProCiv Forest Sapper Assets',
            'ProCiv Forest Sapper Operatives',
            'ProCiv Armed Forces Assets',
            'ProCiv Armed Forces Operatives',
            'ProCiv GIPS Assets',
            'ProCiv GIPS Operatives',
            'ProCiv GNR Assets',
            'ProCiv GNR Operatives',
            'ProCiv PSP Assets',
            'ProCiv PSP Operatives',
            'ProCiv Other Operatives',
            'ProCiv Reinforcement Groups',
            'ProCiv State Of Affairs',
            'ProCiv State Of Affairs Description',
            'ProCiv Active Previous Intervention Plan',
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getData(): Generator
    {
        $builder = $this->repository->createQueryBuilder();

        $this->filter->apply($builder);

        if (! joined($builder, 'parishes')) {
            $builder->join('parishes', 'parishes.id', '=', 'occurrences.parish_id');
        }

        $builder->join('occurrence_types', 'occurrence_types.id', '=', 'occurrences.type_id')
            ->join('occurrence_statuses', 'occurrence_statuses.id', '=', 'occurrences.status_id')
            ->join('prociv_occurrences', 'prociv_occurrences.id', '=', 'occurrences.source_id')
            ->leftJoin('prociv_occurrence_logs', function (JoinClause $join) {
                $join->on('prociv_occurrence_logs.occurrence_id', '=', 'prociv_occurrences.id')
                    ->whereRaw(
                        <<< SQL
                        prociv_occurrence_logs.created_at = (
                            SELECT MAX(created_at) FROM prociv_occurrence_logs
                            WHERE prociv_occurrence_logs.occurrence_id = prociv_occurrences.id
                        )
SQL
                    );
            });

        $builder->addSelect([
            'occurrences.source_type AS source',
            'occurrence_types.name AS type',
            'occurrence_statuses.name AS status',
            'parishes.name AS parish',

            'prociv_occurrences.remote_id AS prociv_id',
            'prociv_occurrences.ground_assets',
            'prociv_occurrences.ground_operatives',
            'prociv_occurrences.aerial_assets',
            'prociv_occurrences.aerial_operatives',

            'prociv_occurrence_logs.rescue_operations_commander',
            'prociv_occurrence_logs.entities_at_the_theatre_of_operations',
            'prociv_occurrence_logs.notes',
            'prociv_occurrence_logs.operational_command_post',
            'prociv_occurrence_logs.medium_aircrafts',
            'prociv_occurrence_logs.heavy_aircrafts',
            'prociv_occurrence_logs.other_aircrafts',
            'prociv_occurrence_logs.medium_helicopters',
            'prociv_occurrence_logs.heavy_helicopters',
            'prociv_occurrence_logs.other_helicopters',
            'prociv_occurrence_logs.fire_fighter_assets',
            'prociv_occurrence_logs.fire_fighter_operatives',
            'prociv_occurrence_logs.special_fire_fighter_force_assets',
            'prociv_occurrence_logs.special_fire_fighter_force_operatives',
            'prociv_occurrence_logs.forest_sapper_assets',
            'prociv_occurrence_logs.forest_sapper_operatives',
            'prociv_occurrence_logs.armed_force_assets',
            'prociv_occurrence_logs.armed_force_operatives',
            'prociv_occurrence_logs.gips_assets',
            'prociv_occurrence_logs.gips_operatives',
            'prociv_occurrence_logs.gnr_assets',
            'prociv_occurrence_logs.gnr_operatives',
            'prociv_occurrence_logs.psp_assets',
            'prociv_occurrence_logs.psp_operatives',
            'prociv_occurrence_logs.other_operatives',
            'prociv_occurrence_logs.reinforcement_groups',
            'prociv_occurrence_logs.state_of_affairs',
            'prociv_occurrence_logs.state_of_affairs_description',
            'prociv_occurrence_logs.active_previous_intervention_plan',
        ]);

        foreach ($builder->cursor() as $occurrence) {
            yield [
                $occurrence->id,
                $occurrence->event_id,
                $occurrence->type,
                $occurrence->status,
                $occurrence->parish,
                $occurrence->locality,
                $occurrence->source,
                $occurrence->latitude,
                $occurrence->longitude,
                $occurrence->started_at->toDateTimeString(),
                optional($occurrence->ended_at)->toDateTimeString(),
                $occurrence->created_at->toDateTimeString(),
                $occurrence->updated_at->toDateTimeString(),

                // ProCiv
                $occurrence->prociv_id,
                $occurrence->ground_assets,
                $occurrence->ground_operatives,
                $occurrence->aerial_assets,
                $occurrence->aerial_operatives,

                // ProCiv Extra
                $occurrence->rescue_operations_commander,
                $occurrence->entities_at_the_theatre_of_operations,
                $occurrence->notes,
                $occurrence->operational_command_post,
                $occurrence->medium_aircrafts,
                $occurrence->heavy_aircrafts,
                $occurrence->other_aircrafts,
                $occurrence->medium_helicopters,
                $occurrence->heavy_helicopters,
                $occurrence->other_helicopters,
                $occurrence->fire_fighter_assets,
                $occurrence->fire_fighter_operatives,
                $occurrence->special_fire_fighter_force_assets,
                $occurrence->special_fire_fighter_force_operatives,
                $occurrence->forest_sapper_assets,
                $occurrence->forest_sapper_operatives,
                $occurrence->armed_force_assets,
                $occurrence->armed_force_operatives,
                $occurrence->gips_assets,
                $occurrence->gips_operatives,
                $occurrence->gnr_assets,
                $occurrence->gnr_operatives,
                $occurrence->psp_assets,
                $occurrence->psp_operatives,
                $occurrence->other_operatives,
                $occurrence->reinforcement_groups,
                $occurrence->state_of_affairs,
                $occurrence->state_of_affairs_description,
                $occurrence->active_previous_intervention_plan,
            ];
        }
    }
}
