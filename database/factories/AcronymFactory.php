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

$factory->define(Acronym::class, static function (Faker $faker) {
    return [
        'initials' => $faker->unique()->lexify(),
        'meaning'  => $faker->unique()->sentence(),
    ];
});
