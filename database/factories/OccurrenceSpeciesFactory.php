<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use VOSTPT\Models\OccurrenceFamily;
use VOSTPT\Models\OccurrenceSpecies;

/*
|--------------------------------------------------------------------------
| OccurrenceSpecies Factories
|--------------------------------------------------------------------------
|
*/

$factory->define(OccurrenceSpecies::class, function (Faker $faker) {
    return [
        'family_id' => function () {
            return factory(OccurrenceFamily::class)->create()->getKey();
        },
        'code' => $faker->unique()->numberBetween(1000, 9999),
        'name' => $faker->unique()->sentence(),
    ];
});
