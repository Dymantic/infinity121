<?php

namespace App\CustomerAffairs;

use App\Calendar\DateFormatter;
use App\Profile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Lesson extends Model
{

    const STATUS_DONE = 'done';

    protected $fillable = ['lesson_date', 'starts', 'ends'];

    protected $casts = ['complete' => 'boolean'];

    protected $dates = ['lesson_date', 'completed_on'];

    public function scopeLogged($query)
    {
        $query->where('complete', true);
    }

    public function scopeRequiresLogging($query)
    {
        $query->where('complete', false)
              ->where('lesson_date', '<', Carbon::today());
    }

    public function scopeDueByTeacher($query, Profile $profile)
    {
        return $query->due()->whereHas('course', function ($query) use ($profile) {
            return $query->where('profile_id', $profile->id)
                         ->whereNotNull('confirmed_on');
        });
    }

    public function scopeDue($query)
    {
        $query->where('complete', false);
    }

    public function scopeCompleted($query)
    {
        $query->where('complete', true);
    }

    public function scopeCompletedByTeacher($query, Profile $profile)
    {
        return $query->where('profile_id', $profile->id);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function log(Profile $teacher, array $log_data)
    {
        $this->profile_id = $teacher->id;
        $this->status = static::STATUS_DONE;
        $this->complete = true;
        $this->completed_on = Carbon::parse($log_data['completed_on']);
        $this->actual_start = $log_data['actual_start'];
        $this->actual_end = $log_data['actual_end'];
        $this->material_taught = $log_data['material_taught'];
        $this->student_report = $log_data['student_report'];
        $this->teacher_log = $log_data['teacher_log'];
        $this->save();

        $this->course->onLessonLogged();
    }

    public function toArray()
    {
        return [
            'id'                  => $this->id,
            'status'              => $this->status,
            'complete'            => $this->complete,
            'lesson_date'         => DateFormatter::standard($this->lesson_date),
            'lesson_day'          => DateFormatter::dayOfWeek($this->lesson_date),
            'lesson_date_pretty'  => DateFormatter::pretty($this->lesson_date),
            'starts'              => $this->starts,
            'ends'                => $this->ends,
            'actual_start'        => $this->actual_start,
            'actual_end'          => $this->actual_end,
            'completed_on'        => DateFormatter::standard($this->completed_on),
            'completed_on_pretty' => DateFormatter::pretty($this->completed_on),
            'material_taught'     => $this->material_taught,
            'student_report'      => $this->student_report,
            'teacher_log'         => $this->teacher_log,
            'profile_id'          => $this->profile_id,
            'teacher_name'        => $this->teacher() ? $this->teacher()->name : '',
            'teacher_avatar'      => $this->teacher() ? $this->teacher()->getAvatar()['thumb'] : Profile::DEFAULT_AVATAR,
            'teacher_id' => $this->teacher()->id,
        ];
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function teacher()
    {
        return $this->profile ? $this->profile : $this->course->teacher;
    }

    public function presentForAdmin()
    {
        $adminInfo = [
            'students' => $this->course->students ?? [['name' => '', 'age' => '']],
        ];

        return array_merge($this->toArray(), $adminInfo);
    }

    public function presentForTeacher()
    {
        $course = $this->course;

        $teacher_info = [
            'students'             => $course->students,
            'location_area'        => $course->area ? $course->area->name : '',
            'location_address'     => $course->address,
            'location_map'         => $course->map_link,
            'location_notes'       => $course->location_notes,
            'lesson_number'        => $course->lessons()->count(),
            'course_total_lessons' => $course->total_lessons,
        ];

        return array_merge($this->toArray(), $teacher_info);
    }
}
