<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Teaching\Subject::class, function (Faker $faker) {
    return [
        'title' => [
            'en' => $faker->words(3, true),
            'zh' => $faker->words(3, true),
        ],
        'description' => [
            'en' => $faker->paragraph,
            'zh' => $faker->paragraph,
        ],
        'writeup' => [
            'en' => $faker->paragraphs(3, true),
            'zh' => $faker->paragraphs(3, true),
        ],
    ];
});
