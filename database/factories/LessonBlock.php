<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CustomerAffairs\Course;
use App\CustomerAffairs\LessonBlock;
use App\Model;
use Faker\Generator as Faker;

$factory->define(LessonBlock::class, function (Faker $faker) {
    return [
        'course_id' => factory(Course::class),
        'day_of_week' => $faker->numberBetween(0,6),
        'starts' => '17:00',
        'ends' => '18:00',
    ];
});
