<?php

namespace App\CustomerAffairs;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = ['lesson_date', 'starts', 'ends'];

    protected $casts = ['complete' => 'boolean'];

    protected $dates = ['lesson_date'];
}
