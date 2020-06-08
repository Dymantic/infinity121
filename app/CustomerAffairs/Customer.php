<?php

namespace App\CustomerAffairs;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'email', 'phone'];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function addCourse(array $data): Course
    {
        return $this->courses()->create([
            'subject_id' => $data['subject_id'],
            'total_lessons' => $data['total_lessons'],
            'students' => $data['students'],
            'starts_from' => $data['starts_from'],
        ]);
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ];
    }
}
