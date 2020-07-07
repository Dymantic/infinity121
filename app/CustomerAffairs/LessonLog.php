<?php


namespace App\CustomerAffairs;


use App\Calendar\Time;
use Illuminate\Support\Carbon;

class LessonLog
{
    public Carbon $completed_on;
    public Time $start;
    public Time $end;
    public string $material_taught;
    public string $student_interaction;
    public string $student_comprehension;
    public string $student_confidence;
    public string $student_output;
    public string $teacher_log;

    public function __construct(array $log_data)
    {
        $this->completed_on = Carbon::parse($log_data['completed_on']);
        $this->start = new Time($log_data['actual_start']);
        $this->end = new Time($log_data['actual_end']);
        $this->material_taught = $log_data['material_taught'];
        $this->teacher_log = $log_data['teacher_log'];
        $this->student_interaction = $log_data['student_interaction'];
        $this->student_comprehension = $log_data['student_comprehension'];
        $this->student_confidence = $log_data['student_confidence'];
        $this->student_output = $log_data['student_output'];
    }
}
