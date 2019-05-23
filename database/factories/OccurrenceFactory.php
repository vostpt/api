<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use VOSTPT\Models\Event;
use VOSTPT\Models\Occurrence;
use VOSTPT\Models\OccurrenceStatus;
use VOSTPT\Models\OccurrenceType;
use VOSTPT\Models\Parish;

/*
|--------------------------------------------------------------------------
| Occurrence Factories
|--------------------------------------------------------------------------
|
*/

$factory->define(Occurrence::class, function (Faker $faker) {
    return [
        'event_id' => function () {
            return factory(Event::class)->create()->id;
        },
        'type_id' => function () {
            return factory(OccurrenceType::class)->create()->id;
        },
        'status_id' => function () {
            return factory(OccurrenceStatus::class)->create()->id;
        },
        'parish_id' => function () {
            return factory(Parish::class)->create()->id;
        },
        'locality'   => $faker->sentence(),
        'latitude'   => $faker->latitude,
        'longitude'  => $faker->longitude,
        'started_at' => $faker->dateTime,
        'ended_at'   => $faker->dateTime,
    ];
});
