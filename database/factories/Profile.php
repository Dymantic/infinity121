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
        'teaching_since' => $faker->numberBetween(2010,2019),
        'chinese_ability' => $faker->numberBetween(1,4),
        'is_public' => false,
        'spoken_languages' => ['en', 'fr'],
    ];
});

$factory->state(\App\Profile::class, 'public', [
    'is_public' => true,
]);

$factory->state(\App\Profile::class, 'private', [
    'is_public' => false,
]);
