<?php

namespace App\Http\Requests;

use App\CustomerAffairs\Course;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class ConfirmCourseRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'course_id' => ['exists:courses,id'],
            'starts_from' => ['required', 'date'],
        ];
    }

    public function course(): Course
    {
        return Course::find($this->course_id);
    }

    public function startFromDate(): Carbon
    {
        return Carbon::parse($this->starts_from);
    }
}
