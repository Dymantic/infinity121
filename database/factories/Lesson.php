<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CustomerAffairs\Course;
use App\CustomerAffairs\Lesson;
use Faker\Generator as Faker;

$factory->define(Lesson::class, function (Faker $faker) {
    return [
        'course_id' => factory(Course::class),
        'lesson_date' => \Illuminate\Support\Carbon::tomorrow(),
        'starts' => '10:00',
        'ends' => '11:00',
        'profile_id' => null,
        'complete' => $faker->boolean,
        'status' => null,
        'teacher_log' => null,
        'material_taught' => null,
        'student_interaction' => null,
        'student_comprehension' => null,
        'student_confidence' => null,
        'student_output' => null,
        'completed_on' => null,
        'actual_start' => null,
        'actual_end' => null,
    ];
});

$factory->state(Lesson::class, 'due', [
    'lesson_date' => \Illuminate\Support\Carbon::tomorrow(),
    'complete' => false,
]);

$factory->state(Lesson::class, 'completed', [
    'lesson_date' => \Illuminate\Support\Carbon::yesterday(),
    'profile_id' => null,
    'complete' => true,
    'status' => 'done',
    'teacher_log' => 'lesson completed smoothly',
    'material_taught' => 'ESL magazine',
    'student_interaction' => 'good',
    'student_comprehension' => 'good',
    'student_confidence' => 'good',
    'student_output' => 'good',
    'completed_on' => \Illuminate\Support\Carbon::yesterday(),
    'actual_start' => '10:00',
    'actual_end' => '12:00',
]);

$factory->state(Lesson::class, 'cancelled', [
    'lesson_date' => \Illuminate\Support\Carbon::yesterday(),
    'profile_id' => null,
    'complete' => true,
    'status' => 'cancelled',
    'teacher_log' => 'cancelled',
    'material_taught' => 'none',
    'student_interaction' => null,
    'student_comprehension' => null,
    'student_confidence' => null,
    'student_output' => null,
    'completed_on' => \Illuminate\Support\Carbon::yesterday(),
    'actual_start' => '10:00',
    'actual_end' => '12:00',
]);
