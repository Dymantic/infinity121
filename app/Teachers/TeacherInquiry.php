<?php

namespace App\Teachers;

use Illuminate\Database\Eloquent\Model;

class TeacherInquiry extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'age',
        'years_in_taiwan',
        'available_hours_per_week',
        'teaching_experience',
    ];
}
