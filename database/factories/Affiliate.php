<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Affiliates\Affiliate::class, function (Faker $faker) {
    return [
        'name' => [
            'en' => $faker->words(3, true),
            'zh' => $faker->words(3, true),
            'jp' => $faker->words(3, true),
        ],
        'description' => [
            'en' => $faker->paragraph,
            'zh' => $faker->paragraph,
            'jp' => $faker->paragraph,
        ],
        'link' => $faker->url,
        'is_public' => $faker->boolean
    ];
});

$factory->state(\App\Affiliates\Affiliate::class, 'private', [
    'is_public' => false,
]);

$factory->state(\App\Affiliates\Affiliate::class, 'public', [
    'is_public' => true,
]);
