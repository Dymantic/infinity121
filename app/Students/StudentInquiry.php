<?php

namespace App\Students;

use App\Teaching\Subject;
use Illuminate\Database\Eloquent\Model;

class StudentInquiry extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'age',
        'subject_id',
        'english_ability',
        'message'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'age' => $this->age,
            'english_ability' => $this->english_ability,
            'address' => $this->address,
            'subject_id' => $this->subject->id,
            'course' => $this->subject->title['en'] ?? 'unknown',
            'message' => $this->message,
        ];
    }
}
