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

$factory->define(ProCivOccurrence::class, static function (Faker $faker) {
    return [
        'remote_id'         => $faker->unique()->numerify('#############'),
        'ground_assets'     => $faker->numberBetween(1, 8),
        'ground_operatives' => $faker->numberBetween(2, 8),
        'aerial_assets'     => $faker->numberBetween(0, 8),
        'aerial_operatives' => $faker->numberBetween(0, 8),
    ];
});
