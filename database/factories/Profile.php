<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Profile::class, function (Faker $faker) {
    return [
        'user_id' => function() { return factory(\App\User::class)->create()->id; },
        'name' => $faker->name,
        'bio' => ['en' => $faker->paragraph, 'zh' => $faker->paragraph],
        'nationality' => $faker->countryCode,
        'qualifications' => 'BFake',
        'teaching_specialties' => 'ESL',
        'years_experience' => $faker->numberBetween(1,10),
        'chinese_ability' => $faker->numberBetween(1,5),

    ];
});
