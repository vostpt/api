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

$factory->define(Parish::class, function (Faker $faker) {
    return [
        'county_id' => function () {
            return factory(County::class)->create()->id;
        },
        'code' => $faker->unique()->numerify('######'),
        'name' => \sprintf('%s Parish', $faker->unique()->name),
    ];
});
