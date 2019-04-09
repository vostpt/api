<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use VOSTPT\Models\User;

/*
|--------------------------------------------------------------------------
| User Factories
|--------------------------------------------------------------------------
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name'     => $faker->firstName,
        'surname'  => $faker->lastName,
        'email'    => $faker->unique()->safeEmail,
        'password' => bcrypt('secret'),
    ];
});
