<?php

namespace App\CustomerAffairs;

use App\Locations\Area;
use App\Profile;
use App\Teaching\Subject;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
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

    protected $dates = ['starts_from'];

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
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'customer' => $this->customer ? $this->customer->toArray() : null,
            'students' => $this->students,
            'total_lessons' => $this->total_lessons,
            'starts_from' => $this->starts_from->format('Y-m-d'),
            'starts_from_pretty' => $this->starts_from->format('jS M, Y'),
            'subject_id' => $this->subject->id,
            'subject_title' => $this->subject->id ? $this->subject->title : ['en' => '', 'zh' => '', 'jp' => ''],
            'lesson_blocks' => $this->lessonBlocks->map(fn ($block) => [
                'day_of_week' => $block->day_of_week, 'starts' => $block->starts, 'ends' => $block->ends
            ])->all(),
            'area_id' => $this->area_id,
            'area' => $this->area_id ? $this->area->toArray() : null,
            'map_link' => $this->map_link,
            'address' => $this->address,
            'location_notes' => $this->location_notes,
            'profile_id' => $this->profile_id,
            'teacher_name' => $this->profile_id ?$this->teacher->name : '',
            'teacher_bio' => $this->teacher ? $this->teacher->bio : ['en' => '', 'zh' => '', 'jp' => ''],
            'teacher_avatar_thumb' => $this->teacher ? $this->teacher->getFirstMediaUrl(Profile::AVATAR, 'thumb') : ''
        ];
    }
}
