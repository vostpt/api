<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use VOSTPT\Models\ProCivOccurrenceSpecies;
use VOSTPT\Models\ProCivOccurrenceType;

/*
|--------------------------------------------------------------------------
| ProCivOccurrenceType Factories
|--------------------------------------------------------------------------
|
*/

$factory->define(ProCivOccurrenceType::class, function (Faker $faker) {
    return [
        'species_id' => function () {
            return factory(ProCivOccurrenceSpecies::class)->create()->id;
        },
        'code' => $faker->unique()->numberBetween(1000, 9999),
        'name' => $faker->unique()->sentence(),
    ];
});
