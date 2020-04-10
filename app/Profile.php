<?php

namespace App;

use App\Teaching\Subject;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Profile extends Model implements HasMedia
{
    use Translatable, HasMediaTrait, Sluggable;

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

    public function sluggable()
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
            $q->where('is_teacher', true);
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeInOrder($query)
    {
        return $query->orderByRaw('ISNULL(position), position ASC');
    }

    public static function setOrder(array $ordered_ids)
    {
        collect($ordered_ids)->each(
            fn ($id, $position) => static::setProfilePosition($id, $position)
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

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
             ->fit(Manipulations::FIT_CROP, 400, 400)
             ->keepOriginalImageFormat()
             ->optimize()
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

    public function toArray()
    {
        $avatar = $this->getFirstMedia(static::AVATAR);

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
            'avatar_original'       => $avatar ? $avatar->getUrl() : static::DEFAULT_AVATAR,
            'avatar_thumb'          => $avatar ? $avatar->getUrl('thumb') : static::DEFAULT_AVATAR,
            'is_public'             => $this->is_public,
            'subjects'              => $this->subjects->map->toArray()->all(),
            'spoken_language_codes' => $this->spoken_languages,
            'spoken_languages'      => $this->spokenLanguageNames(),
            'position'              => $this->position,
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
            'bio'           => $this->bio[app()->getLocale()] ?? '',
            'nationality'   => Nationalities::byCode($this->nationality)[app()->getLocale()] ?? '',
            'subject_names' => $this->subjects->pluck('title')->map(function ($name) {
                return $name[app()->getLocale()] ?? '';
            })->reject(function ($name) {
                return $name === "";
            })->all(),

        ]);
    }
}
