<?php

namespace App;

use App\Calendar\Day;
use App\Calendar\TimePeriod;
use App\CustomerAffairs\Course;
use App\Locations\Area;
use App\Teaching\AvailablePeriod;
use App\Teaching\Subject;
use App\Teaching\UnavailablePeriod;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Profile extends Model implements HasMedia
{
    use Translatable, InteractsWithMedia, Sluggable;

    const AVATAR = 'avatar';
    const DEFAULT_AVATAR = '/images/default_user.svg';

    protected $guarded = [];

    protected $casts = ['bio' => 'array', 'is_public' => 'boolean', 'spoken_languages' => 'array'];

    public $translatable = ['bio'];

    private $ability_scale = [
        1 => 'little',
        2 => 'some',
        3 => 'good',
        4 => 'very good',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeTeachers($query)
    {
        return $query->whereHas('user', function ($q) {
            $q->where('removed', false)->where('is_teacher', true);

        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeTeachingIn($query, $area_id)
    {
        return $query->whereHas('workingAreas', fn($q) => $q->where("areas.id", $area_id));
    }

    public function scopeCanTeach($query, $subject_id)
    {
        return $query->whereHas('subjects', fn($q) => $q->where('subjects.id', $subject_id));
    }

    public function scopeAvailableFor($query, $lessons)
    {
        foreach ($lessons as $lesson) {
            $query->whereHas('availablePeriods', function ($q) use ($lesson) {
                $q->where([
                    ['day_of_week', $lesson['day_of_week']],
                    ['starts', '<=', intval($lesson['starts'])],
                    ['ends', '>=', intval($lesson['ends'])]
                ]);
            });
        }
    }

    public function scopeInOrder($query)
    {
        return $query->orderByRaw('IFNULL(position,99999), position ASC');
    }

    public static function setOrder(array $ordered_ids)
    {
        collect($ordered_ids)->each(
            fn($id, $position) => static::setProfilePosition($id, $position)
        );
    }

    private static function setProfilePosition($id, $position)
    {
        optional(static::find($id))->update(['position' => $position + 1]);
    }

    public function setProfileImage($file)
    {
        $this->clearMediaCollection(static::AVATAR);

        return $this->addMedia($file)
                    ->preservingOriginal()
                    ->toMediaCollection(static::AVATAR);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
             ->fit(Manipulations::FIT_CROP, 400, 400)
             ->keepOriginalImageFormat()
             ->optimize()
             ->nonQueued()
             ->performOnCollections(static::AVATAR);
    }

    public function publish()
    {
        $this->is_public = true;
        $this->save();
    }

    public function retract()
    {
        $this->is_public = false;
        $this->save();
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }

    public function assignSubjects($subject_ids)
    {
        $this->subjects()->sync($subject_ids);
    }

    public function getAvatar()
    {
        $avatar = $this->getFirstMedia(static::AVATAR);

        return [
            'original' => $avatar ? $avatar->getUrl() : self::DEFAULT_AVATAR,
            'thumb'    => $avatar ? $avatar->getUrl('thumb') : self::DEFAULT_AVATAR,
        ];
    }

    public function toArray()
    {
        $avatar = $this->getAvatar();
        $this->load('workingAreas.region.country');

        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'slug'                  => $this->slug,
            'bio'                   => $this->bio,
            'nationality'           => Nationalities::byCode($this->nationality),
            'country_code'          => $this->nationality,
            'qualifications'        => $this->qualifications,
            'teaching_specialties'  => $this->teaching_specialties,
            'teaching_since'        => $this->teaching_since,
            'years_experience'      => Carbon::now()->year - $this->teaching_since,
            'chinese_ability'       => $this->chinese_ability,
            'chinese_ability_full'  => $this->ability_scale[$this->chinese_ability] ?? '',
            'avatar_original'       => $avatar['original'],
            'avatar_thumb'          => $avatar['thumb'],
            'is_public'             => $this->is_public,
            'subjects'              => $this->subjects->map->toArray()->all(),
            'spoken_language_codes' => $this->spoken_languages,
            'spoken_languages'      => $this->spokenLanguageNames(),
            'position'              => $this->position,
            'working_areas'         => $this->workingAreas->map->toArray()->all(),
        ];
    }

    private function spokenLanguageNames()
    {
        $languages = [
            'en' => 'english',
            'sp' => 'spanish',
            'jp' => 'japanese',
            'zh' => 'chinese',
            'fr' => 'french',
            'de' => 'german',
        ];

        return collect($this->spoken_languages)->map(function ($code) use ($languages) {
            return $languages[$code];
        })->all();
    }

    public function forCurrentLang()
    {
        return array_merge($this->toArray(), [
            'bio'           => $this->getTranslation('bio', app()->getLocale(), 'en'),
            'nationality'   => Nationalities::byCode($this->nationality)[app()->getLocale()] ?? '',
            'subject_names' => $this->subjects->pluck('title')->map(function ($name) {
                return $name[app()->getLocale()] ?? '';
            })->reject(function ($name) {
                return $name === "";
            })->all(),

        ]);
    }


    public function courses()
    {
        return $this->hasMany(Course::class, 'profile_id');
    }

    public function availablePeriods()
    {
        return $this->hasMany(AvailablePeriod::class);
    }

    public function setAvailabilityForDay(Day $day)
    {
        $this->availablePeriods()->where('day_of_week', $day->week_day)->get()->each->delete();

        $lessons = $this->courses()
                        ->with('lessonBlocks')
                        ->get()
                        ->flatMap(fn(Course $c) => $c->lessonBlocks->all())
                        ->reject(fn($l) => $l->day_of_week !== $day->week_day)->all();

        foreach ($lessons as $lesson) {
            $day = $day->clearPeriod(new TimePeriod($lesson->starts, $lesson->ends));
        }


        $day->periods->each(fn(TimePeriod $p) => $this->availablePeriods()->create([
            'day_of_week' => $day->week_day,
            'starts'      => $p->startAsInt(),
            'ends'        => $p->endAsInt(),
        ]));
    }

    public function availablePeriodsSummary()
    {
        return $this
            ->availablePeriods
            ->reduce(function ($carry, $period) {
                $carry[$period->day_of_week]['periods'][] = $period->timeStringTuple();

                return $carry;
            }, [
                Carbon::MONDAY    => ['periods' => []],
                Carbon::TUESDAY   => ['periods' => []],
                Carbon::WEDNESDAY => ['periods' => []],
                Carbon::THURSDAY  => ['periods' => []],
                Carbon::FRIDAY    => ['periods' => []],
                Carbon::SATURDAY  => ['periods' => []],
                Carbon::SUNDAY    => ['periods' => []],
            ]);

    }

    public function currentSchedule()
    {
        $available = $this->availablePeriodsSummary();

        $confirmed = $this
            ->courses()
            ->confirmed()
            ->with('lessonBlocks')
            ->get()
            ->reduce(function ($carry, $course) {
                foreach ($course->lessonBlocks as $block) {
                    $carry[$block->day_of_week]['periods'][] = $block->toTimeTuple();
                }

                return $carry;
            }, [
                Carbon::MONDAY    => ['periods' => []],
                Carbon::TUESDAY   => ['periods' => []],
                Carbon::WEDNESDAY => ['periods' => []],
                Carbon::THURSDAY  => ['periods' => []],
                Carbon::FRIDAY    => ['periods' => []],
                Carbon::SATURDAY  => ['periods' => []],
                Carbon::SUNDAY    => ['periods' => []],
            ]);

        $unconfirmed = $this
            ->courses()
            ->unconfirmed()
            ->with('lessonBlocks')
            ->get()
            ->reduce(function ($carry, $course) {
                foreach ($course->lessonBlocks as $block) {
                    $carry[$block->day_of_week]['periods'][] = $block->toTimeTuple();
                }

                return $carry;
            }, [
                Carbon::MONDAY    => ['periods' => []],
                Carbon::TUESDAY   => ['periods' => []],
                Carbon::WEDNESDAY => ['periods' => []],
                Carbon::THURSDAY  => ['periods' => []],
                Carbon::FRIDAY    => ['periods' => []],
                Carbon::SATURDAY  => ['periods' => []],
                Carbon::SUNDAY    => ['periods' => []],
            ]);

//        dd($available);

        return [
            'available'   => $available,
            'confirmed'   => $confirmed,
            'unconfirmed' => $unconfirmed,
        ];
    }

    public function unavailablePeriods()
    {
        return $this->hasMany(UnavailablePeriod::class);
    }

    public function clearTimeBlock(array $timeBlock)
    {
        $day = $this->getDay($timeBlock['day_of_week']);

        $day = $day->clearPeriod(new TimePeriod($timeBlock['starts'], $timeBlock['ends']));

        $this->setAvailabilityForDay($day);

    }

    public function allocateTimeBlock(array $timeBlock)
    {
        $day = $this->getDay($timeBlock['day_of_week']);

        $day = $day->addPeriod(new TimePeriod($timeBlock['starts'], $timeBlock['ends']));

        $this->setAvailabilityForDay($day);
    }

    public function getDay(int $day_of_week): Day
    {
        $periods = $this->availablePeriods()
                        ->where('day_of_week', $day_of_week)
                        ->get();

        $day = new Day($day_of_week);
        foreach ($periods as $period) {
            $day = $day->addPeriod($period->timePeriod());
        }

        return $day;
    }

    public function setUnavailablePeriod($from, $to)
    {
        return $this->unavailablePeriods()->create([
            'starts' => $from,
            'ends'   => $to,
        ]);
    }

    public function workingAreas()
    {
        return $this->belongsToMany(Area::class);
    }

    public function setWorkingAreas(array $area_ids)
    {
        $this->workingAreas()->sync($area_ids);
    }
}
