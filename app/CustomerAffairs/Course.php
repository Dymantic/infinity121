<?php

namespace App\CustomerAffairs;

use App\Calendar\DateFormatter;
use App\Locations\Area;
use App\Profile;
use App\Teaching\Subject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Course extends Model
{
    const STATUS_UNCONFIRMED = 'unconfirmed';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_ONGOING = 'ongoing';

    protected $fillable = [
        'total_lessons',
        'subject_id',
        'students',
        'starts_from',
        'area_id',
        'address',
        'map_link',
        'location_notes',
    ];

    protected $casts = ['students' => 'array'];

    protected $dates = ['starts_from', 'confirmed_on'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function lessonBlocks()
    {
        return $this->hasMany(LessonBlock::class);
    }

    public function setLessonBlocks(array $blocks)
    {
        $this->lessonBlocks()->delete();

        return collect($blocks)
            ->map(fn($block) => $this->createBlock($block));
    }

    private function createBlock(array $block)
    {
        return $this->lessonBlocks()->create($block);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function setLocationData(array $data)
    {
        $this->update($data);
    }

    public function teacher()
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }

    public function clearTeacher()
    {
        $this->profile_id = null;
        $this->save();
    }

    public function assignTeacher(int $profile_id)
    {
        $this->profile_id = $profile_id;
        $this->save();
    }

    public function toArray()
    {
        return [
            'id'                   => $this->id,
            'status'               => $this->status(),
            'customer_id'          => $this->customer_id,
            'customer'             => $this->customer ? $this->customer->toArray() : null,
            'students'             => $this->students,
            'total_lessons'        => $this->total_lessons,
            'starts_from'          => DateFormatter::standard($this->starts_from),
            'starts_from_pretty'   => DateFormatter::pretty($this->starts_from),
            'subject_id'           => $this->subject->id,
            'subject_title'        => $this->subject->id ? $this->subject->title : ['en' => '', 'zh' => '', 'jp' => ''],
            'lesson_blocks'        => $this->lessonBlocks->map(fn($block) => [
                'day_of_week' => $block->day_of_week,
                'starts'      => $block->starts,
                'ends'        => $block->ends
            ])->all(),
            'area_id'              => $this->area_id,
            'area'                 => $this->area_id ? $this->area->toArray() : null,
            'map_link'             => $this->map_link,
            'address'              => $this->address,
            'location_notes'       => $this->location_notes,
            'profile_id'           => $this->profile_id,
            'teacher_name'         => $this->profile_id ? $this->teacher->name : '',
            'teacher_bio'          => $this->teacher ? $this->teacher->bio : ['en' => '', 'zh' => '', 'jp' => ''],
            'teacher_avatar_thumb' => $this->teacher ? $this->teacher->getFirstMediaUrl(Profile::AVATAR, 'thumb') : '',
            'lessons' => []
        ];
    }

    public function confirm(Carbon $starts_from)
    {
        $this->starts_from = $starts_from;
        $this->confirmed_on = Carbon::today();
        $this->save();

    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function setNextLesson()
    {
        if(!$this->starts_from) {
            return;
        }

        $after = $this->starts_from->isFuture() ? $this->starts_from : Carbon::now();

        $nextLessonBlock = $this
            ->lessonBlocks
            ->sort(fn ($a, $b) => $a->asNextDate($after)->unix() - $b->asNextDate($after)->unix())
        ->first();
//        dd($nextLessonBlock->asNextDate($after)->weekDay());

        return $this->lessons()->create([
            'lesson_date' => $nextLessonBlock->asNextDate($after),
            'starts' => $nextLessonBlock->starts,
            'ends' => $nextLessonBlock->starts,
        ]);
    }

    public function isConfirmed()
    {
        return !!$this->confirmed_on;
    }

    public function status(): string
    {
        if (!$this->isConfirmed()) {
            return static::STATUS_UNCONFIRMED;
        }

        if($this->starts_from && $this->starts_from->isPast()) {
            return static::STATUS_ONGOING;
        }

        return static::STATUS_CONFIRMED;
    }
}
