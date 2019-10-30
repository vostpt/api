<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use VOSTPT\Models\County;
use VOSTPT\Models\Parish;

/*
|--------------------------------------------------------------------------
| Parish Factories
|--------------------------------------------------------------------------
|
*/

$factory->define(Parish::class, static function (Faker $faker) {
    return [
        'county_id' => static function () {
            return factory(County::class)->create()->getKey();
        },
        'code' => $faker->unique()->numerify('######'),
        'name' => \sprintf('%s Parish', $faker->unique()->name),
    ];
});
