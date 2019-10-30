<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use VOSTPT\Models\Event;
use VOSTPT\Models\EventType;
use VOSTPT\Models\Parish;

/*
|--------------------------------------------------------------------------
| Event Factories
|--------------------------------------------------------------------------
|
*/

$factory->define(Event::class, static function (Faker $faker) {
    return [
        'type_id' => static function () {
            return factory(EventType::class)->create()->getKey();
        },
        'parish_id' => static function () {
            return factory(Parish::class)->create()->getKey();
        },
        'name'        => $faker->sentence(),
        'description' => $faker->paragraph,
        'latitude'    => $faker->latitude,
        'longitude'   => $faker->longitude,
        'started_at'  => $faker->dateTime,
        'ended_at'    => $faker->dateTime,
    ];
});
