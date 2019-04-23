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

$factory->define(Event::class, function (Faker $faker) {
    return [
        'type_id' => function () {
            return factory(EventType::class)->create()->id;
        },
        'parish_id' => function () {
            return factory(Parish::class)->create()->id;
        },
        'name'        => $faker->sentence(),
        'description' => $faker->paragraph,
        'latitude'    => $faker->latitude,
        'longitude'   => $faker->longitude,
        'started_at'  => $faker->dateTime,
        'ended_at'    => $faker->dateTime,
    ];
});
