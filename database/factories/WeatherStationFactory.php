<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use VOSTPT\Models\County;
use VOSTPT\Models\WeatherStation;

/*
|--------------------------------------------------------------------------
| WeatherStation Factories
|--------------------------------------------------------------------------
|
*/

$factory->define(WeatherStation::class, static function (Faker $faker) {
    return [
        'county_id' => static function () {
            return factory(County::class)->create()->getKey();
        },
        'entity' => $faker->unique()->company,
        'name'   => \sprintf('%s Station', $faker->unique()->name),
        'serial' => $faker->unique()->numerify('###'),
    ];
});
