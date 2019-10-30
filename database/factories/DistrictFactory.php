<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use VOSTPT\Models\District;

/*
|--------------------------------------------------------------------------
| District Factories
|--------------------------------------------------------------------------
|
*/

$factory->define(District::class, static function (Faker $faker) {
    return [
        'code' => $faker->unique()->numerify('######'),
        'name' => \sprintf('%s District', $faker->unique()->name),
    ];
});
