<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use VOSTPT\Models\ProCivOccurrenceFamily;
use VOSTPT\Models\ProCivOccurrenceSpecies;

/*
|--------------------------------------------------------------------------
| ProCivOccurrenceSpecies Factories
|--------------------------------------------------------------------------
|
*/

$factory->define(ProCivOccurrenceSpecies::class, function (Faker $faker) {
    return [
        'family_id' => function () {
            return factory(ProCivOccurrenceFamily::class)->create()->id;
        },
        'code' => $faker->unique()->numberBetween(1000, 9999),
        'name' => $faker->unique()->sentence(),
    ];
});
