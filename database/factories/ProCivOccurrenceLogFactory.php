<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use VOSTPT\Models\ProCivOccurrence;
use VOSTPT\Models\ProCivOccurrenceLog;

/*
|--------------------------------------------------------------------------
| ProCivOccurrenceLog Factories
|--------------------------------------------------------------------------
|
*/

$factory->define(ProCivOccurrenceLog::class, function (Faker $faker) {
    return [
        'occurrence_id' => function () {
            return factory(ProCivOccurrence::class)->create()->id;
        },

        'rescue_operations_commander'           => $faker->name,
        'entities_at_the_theatre_of_operations' => $faker->company,
        'notes'                                 => $faker->paragraphs,

        'operational_command_post' => $faker->streetAddress,

        'medium_aircrafts_involved' => $faker->numberBetween(0, 4),
        'heavy_aircrafts_involved'  => $faker->numberBetween(0, 4),
        'other_aircrafts_involved'  => $faker->numberBetween(0, 4),

        'medium_helicopters_involved' => $faker->numberBetween(0, 4),
        'heavy_helicopters_involved'  => $faker->numberBetween(0, 4),
        'other_helicopters_involved'  => $faker->numberBetween(0, 4),

        'fire_fighter_assets_involved'     => $faker->numberBetween(4, 32),
        'fire_fighter_operatives_involved' => $faker->numberBetween(8, 256),

        'special_fire_fighter_force_assets_involved'     => $faker->numberBetween(0, 4),
        'special_fire_fighter_force_operatives_involved' => $faker->numberBetween(0, 16),

        'forest_sapper_assets_involved'     => $faker->numberBetween(0, 4),
        'forest_sapper_operatives_involved' => $faker->numberBetween(0, 16),

        'armed_force_assets_involved'     => $faker->numberBetween(0, 4),
        'armed_force_operatives_involved' => $faker->numberBetween(0, 16),

        'gips_assets_involved'     => $faker->numberBetween(0, 8),
        'gips_operatives_involved' => $faker->numberBetween(0, 32),

        'gnr_assets_involved'     => $faker->numberBetween(0, 8),
        'gnr_operatives_involved' => $faker->numberBetween(0, 64),

        'psp_assets_involved'     => $faker->numberBetween(0, 4),
        'psp_operatives_involved' => $faker->numberBetween(0, 16),

        'other_operatives_involved' => $faker->numberBetween(0, 16),

        'reinforcement_groups_involved' => $faker->sentence,

        'state_of_affairs'             => $faker->sentence,
        'state_of_affairs_description' => $faker->paragraphs,

        'active_previous_intervention_plan' => $faker->sentence,
    ];
});
