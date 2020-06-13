<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Teaching\AvailablePeriod;
use Faker\Generator as Faker;

$factory->define(AvailablePeriod::class, function (Faker $faker) {
    return [
        'profile_id' => factory(\App\Profile::class),
        'day_of_week' => $faker->numberBetween(0,6),
        'starts' => 1000,
        'ends' => 1200
    ];
});
