<?php

namespace App\Teaching;

use App\Profile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class UnavailablePeriod extends Model
{
    protected $fillable = ['starts', 'ends'];

    protected $dates = ['starts', 'ends'];

    public static function booted()
    {
        static::addGlobalScope('upcoming', fn($builder) => $builder->where('ends', '>=', Carbon::now()));
    }

    public function teacher()
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'profile_id' => $this->profile_id,
            'teacher_name' => $this->teacher->name,
            'starts' => $this->starts->format('Y-m-d H:i:s'),
            'ends' => $this->ends->format('Y-m-d H:i:s'),
            'starts_date' => $this->starts->format('Y-m-d'),
            'ends_date' => $this->ends->format('Y-m-d'),
            'starts_time' => $this->starts->format('H:i'),
            'ends_time' => $this->ends->format('H:i'),
            'starts_pretty' => $this->starts->format('jS M, Y (H:i)'),
            'ends_pretty' => $this->ends->format('jS M, Y (H:i)'),
        ];
    }
}
