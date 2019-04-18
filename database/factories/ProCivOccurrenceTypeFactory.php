<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use VOSTPT\Models\ProCivOccurrenceType;

/*
|--------------------------------------------------------------------------
| ProCivOccurrenceType Factories
|--------------------------------------------------------------------------
|
*/

$factory->define(ProCivOccurrenceType::class, function (Faker $faker) {
    return [
        'code' => $faker->unique()->numerify('####'),
        'name' => $faker->unique()->sentence(),
    ];
});
