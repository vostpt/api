<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use VOSTPT\Models\EventType;

/*
|--------------------------------------------------------------------------
| EventType Factories
|--------------------------------------------------------------------------
|
*/

$factory->define(EventType::class, static function (Faker $faker) {
    return [
        'name' => $faker->unique()->sentence(),
    ];
});
