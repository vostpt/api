<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use VOSTPT\Models\ProCivOccurrence;
use VOSTPT\Models\ProCivOccurrenceStatus;
use VOSTPT\Models\ProCivOccurrenceType;

/*
|--------------------------------------------------------------------------
| ProCivOccurrence Factories
|--------------------------------------------------------------------------
|
*/

$factory->define(ProCivOccurrence::class, function (Faker $faker) {
    return [
        'type_id' => function () {
            return factory(ProCivOccurrenceType::class)->create()->id;
        },
        'status_id' => function () {
            return factory(ProCivOccurrenceStatus::class)->create()->id;
        },
        'remote_id'                  => $faker->unique()->lexify('?????????????'),
        'ground_assets_involved'     => $faker->numberBetween(1, 8),
        'ground_operatives_involved' => $faker->numberBetween(2, 8),
        'aerial_assets_involved'     => $faker->numberBetween(0, 8),
        'aerial_operatives_involved' => $faker->numberBetween(0, 8),
    ];
});
