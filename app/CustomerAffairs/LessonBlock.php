<?php

namespace App\CustomerAffairs;

use Illuminate\Database\Eloquent\Model;

class LessonBlock extends Model
{
    protected $fillable = ['day_of_week', 'starts', 'ends'];
}
