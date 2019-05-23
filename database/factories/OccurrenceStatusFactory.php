<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use VOSTPT\Models\OccurrenceStatus;

/*
|--------------------------------------------------------------------------
| OccurrenceStatus Factories
|--------------------------------------------------------------------------
|
*/

$factory->define(OccurrenceStatus::class, function (Faker $faker) {
    return [
        'code' => $faker->unique()->numberBetween(1, 255),
        'name' => $faker->unique()->sentence(),
    ];
});
