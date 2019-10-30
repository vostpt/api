<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use VOSTPT\Models\WeatherSensor;
use VOSTPT\Models\WeatherStation;

/*
|--------------------------------------------------------------------------
| WeatherSensor Factories
|--------------------------------------------------------------------------
|
*/

$factory->define(WeatherSensor::class, static function (Faker $faker) {
    return [
        'station_id' => static function () {
            return factory(WeatherStation::class)->create()->getKey();
        },
        'type'       => $faker->name,
        'latitude'   => $faker->latitude,
        'longitude'  => $faker->longitude,
        'altitude'   => $faker->numberBetween(1, 1800),
        'started_at' => $faker->date(),
    ];
});
