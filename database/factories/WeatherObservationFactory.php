<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use VOSTPT\Models\WeatherObservation;
use VOSTPT\Models\WeatherStation;

/*
|--------------------------------------------------------------------------
| WeatherObservation Factories
|--------------------------------------------------------------------------
|
*/

$factory->define(WeatherObservation::class, static function (Faker $faker) {
    return [
        'station_id' => static function () {
            return factory(WeatherStation::class)->create()->getKey();
        },
        'temperature'          => $faker->randomFloat(1, 1, 50),
        'humidity'             => $faker->randomFloat(1, 2, 100),
        'wind_speed'           => $faker->randomFloat(1, 0, 120),
        'wind_direction'       => $faker->randomElement(WeatherObservation::WIND_DIRECTIONS),
        'precipitation'        => $faker->randomFloat(1, 0, 2000),
        'atmospheric_pressure' => $faker->randomFloat(1, 1010, 1030),
        'radiation'            => $faker->randomFloat(1, 0, 3600),
        'timestamp'            => $faker->dateTime(),
    ];
});
