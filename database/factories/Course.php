<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CustomerAffairs\Course;
use App\CustomerAffairs\Customer;
use App\Teaching\Subject;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$factory->define(Course::class, function (Faker $faker) {
    return [
        'customer_id' => factory(Customer::class),
        'subject_id' => factory(Subject::class),
        'students' => ['name' => $faker->name, 'age' => 'adult'],
        'starts_from' => null,
        'confirmed_on' => null,
        'total_lessons' => 20,
    ];
});

$factory->state(Course::class, 'unconfirmed', [
    'starts_from' => null,
    'confirmed_on' => null,
]);

$factory->state(Course::class, 'confirmed', [
    'starts_from' => Carbon::yesterday(),
    'confirmed_on' => Carbon::yesterday(),
]);
