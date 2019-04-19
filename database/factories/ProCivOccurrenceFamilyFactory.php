<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use VOSTPT\Models\ProCivOccurrenceFamily;

/*
|--------------------------------------------------------------------------
| ProCivOccurrenceFamily Factories
|--------------------------------------------------------------------------
|
*/

$factory->define(ProCivOccurrenceFamily::class, function (Faker $faker) {
    return [
        'code' => $faker->unique()->numberBetween(1000, 9999),
        'name' => $faker->unique()->sentence(),
    ];
});
