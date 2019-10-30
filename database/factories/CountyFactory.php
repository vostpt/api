<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use VOSTPT\Models\County;
use VOSTPT\Models\District;

/*
|--------------------------------------------------------------------------
| County Factories
|--------------------------------------------------------------------------
|
*/

$factory->define(County::class, static function (Faker $faker) {
    return [
        'district_id' => static function () {
            return factory(District::class)->create()->getKey();
        },
        'code' => $faker->unique()->numerify('######'),
        'name' => \sprintf('%s County', $faker->unique()->name),
    ];
});
