<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use VOSTPT\Models\Acronym;

/*
|--------------------------------------------------------------------------
| Acronym Factories
|--------------------------------------------------------------------------
|
*/

$factory->define(Acronym::class, function (Faker $faker) {
    return [
        'initials' => $faker->unique()->word,
        'meaning'  => $faker->unique()->sentence(),
    ];
});
