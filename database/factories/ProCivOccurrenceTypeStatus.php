<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use VOSTPT\Models\ProCivOccurrenceStatus;

/*
|--------------------------------------------------------------------------
| ProCivOccurrenceType Factories
|--------------------------------------------------------------------------
|
*/

$factory->define(ProCivOccurrenceStatus::class, function (Faker $faker) {
    return [
        'code' => $faker->unique()->numberBetween(1, 99),
        'name' => $faker->unique()->sentence(),
    ];
});
