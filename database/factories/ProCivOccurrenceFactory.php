<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use VOSTPT\Models\ProCivOccurrence;

/*
|--------------------------------------------------------------------------
| ProCivOccurrence Factories
|--------------------------------------------------------------------------
|
*/

$factory->define(ProCivOccurrence::class, function (Faker $faker) {
    return [
        'remote_id'                  => $faker->unique()->numerify('#############'),
        'ground_assets_involved'     => $faker->numberBetween(1, 8),
        'ground_operatives_involved' => $faker->numberBetween(2, 8),
        'aerial_assets_involved'     => $faker->numberBetween(0, 8),
        'aerial_operatives_involved' => $faker->numberBetween(0, 8),
    ];
});
