<?php

namespace App\Http\Requests;

use App\Calendar\Time;
use App\CustomerAffairs\LessonLog;
use App\Rules\TimeOfDay;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class LogLessonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'completed_on' => ['required', 'date'],
            'actual_start' => ['required', new TimeOfDay()],
            'actual_end' => ['required', new TimeOfDay(), 'after:actual_start'],
            'material_taught' => ['required'],
            'teacher_log' => ['required'],
            'student_interaction' => ['required', 'in:poor,okay,good,excellent'],
            'student_comprehension' => ['required', 'in:poor,okay,good,excellent'],
            'student_confidence' => ['required', 'in:poor,okay,good,excellent'],
            'student_output' => ['required', 'in:poor,okay,good,excellent'],
        ];
    }

    public function lessonLog(): LessonLog
    {
        return new LessonLog([
            'completed_on'          => $this->completed_on,
            'actual_start'          => $this->actual_start,
            'actual_end'            => $this->actual_end,
            'material_taught'       => $this->input('material_taught') ?? '',
            'teacher_log'           => $this->input('teacher_log') ?? '',
            'student_interaction'   => $this->student_interaction,
            'student_comprehension' => $this->student_comprehension,
            'student_confidence'    => $this->student_confidence,
            'student_output'        => $this->student_output,
        ]);
    }
}
