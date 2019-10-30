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

$factory->define(Occurrence::class, static function (Faker $faker) {
    return [
        'event_id' => static function () {
            return factory(Event::class)->create()->getKey();
        },
        'type_id' => static function () {
            return factory(OccurrenceType::class)->create()->getKey();
        },
        'status_id' => static function () {
            return factory(OccurrenceStatus::class)->create()->getKey();
        },
        'parish_id' => static function () {
            return factory(Parish::class)->create()->getKey();
        },
        'locality'   => $faker->sentence(),
        'latitude'   => $faker->latitude,
        'longitude'  => $faker->longitude,
        'started_at' => $faker->dateTime,
        'ended_at'   => $faker->dateTime,
    ];
});
