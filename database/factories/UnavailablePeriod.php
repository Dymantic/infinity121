<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Teaching\UnavailablePeriod;
use Faker\Generator as Faker;

$factory->define(UnavailablePeriod::class, function (Faker $faker) {
    return [
        'profile_id' => factory(\App\Profile::class),
        'starts' => \Illuminate\Support\Carbon::today()->setHour(8),
        'ends' => \Illuminate\Support\Carbon::tomorrow()->setHour(17),
    ];
});
