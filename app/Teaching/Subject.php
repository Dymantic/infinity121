<?php

namespace App\Teaching;

use App\Profile;
use App\Translatable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Subject extends Model implements HasMedia
{

    use Sluggable, Translatable, HasMediaTrait;

    const TITLE_IMAGES = 'title-images';

    public $translatable = ['title', 'description', 'writeup'];

    protected $guarded = [];

    protected $casts = [
        'title'       => 'array',
        'description' => 'array',
        'writeup'     => 'array',
        'is_public'   => 'boolean',
    ];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'slugtitle'
            ]
        ];
    }

    public function profiles()
    {
        return $this->belongsToMany(Profile::class);
    }


    public function getSlugtitleAttribute()
    {
        return $this->translated('title', 'en');
    }

    public static function createNew($english_title)
    {
        $subject_data = [
            'title'       => ['en' => $english_title],
            'description' => ['en' => ''],
            'writeup'     => ['en' => ''],
        ];

        return static::create($subject_data);
    }

    public function safeDelete()
    {
        $this->profiles()->sync([]);
        $this->delete();
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

    public function setTitleImage($file)
    {
        $this->clearMediaCollection(static::TITLE_IMAGES);

        return $this->addMedia($file)
                    ->preservingOriginal()
                    ->toMediaCollection(static::TITLE_IMAGES);
    }

    public function titleImage($conversion = '')
    {
        $title_image = $this->getFirstMedia(static::TITLE_IMAGES);

        if (!$title_image) {
            return null;
        }

        return $title_image->getUrl($conversion);
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
             ->fit(Manipulations::FIT_CROP, 400, 300)
             ->keepOriginalImageFormat()
             ->optimize()
             ->performOnCollections(static::TITLE_IMAGES);

        $this->addMediaConversion('web')
             ->fit(Manipulations::FIT_CROP, 800, 600)
             ->keepOriginalImageFormat()
             ->optimize()
             ->performOnCollections(static::TITLE_IMAGES);
    }

    public function toArray()
    {
        return [
            'id'          => $this->id,
            'slug'        => $this->slug,
            'title'       => $this->title,
            'description' => $this->description,
            'writeup'     => $this->writeup,
            'is_public'   => $this->is_public,
            'title_image' => [
                'thumb'    => $this->titleImage('thumb'),
                'web'      => $this->titleImage('web'),
                'original' => $this->titleImage(),
            ]
        ];
    }

    public function forCurrentLang()
    {
        $lang = app()->getLocale();

        return array_merge($this->toArray(), [
            'title'       => $this->title[$lang] ?? '',
            'description' => $this->description[$lang] ?? '',
            'writeup'     => $this->writeup[$lang] ?? '',
        ]);
    }
}
