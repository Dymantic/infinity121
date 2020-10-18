<?php

namespace App\Affiliates;

use App\Translatable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Affiliate extends Model implements HasMedia
{
    use Translatable, InteractsWithMedia;

    const LOGO = 'logos';
    const DEFAULT_IMG = '/images/default_3x2.svg';

    protected $guarded = [];

    public $translatable = ['name', 'description'];

    protected $casts = [
        'name'        => 'array',
        'description' => 'array',
        'is_public'   => 'boolean',
    ];

    public static function createNew($affiliate_data)
    {
        $attributes = [
            'name'        => [
                'en' => $affiliate_data['name']['en'] ?? "",
                'zh' => $affiliate_data['name']['zh'] ?? "",
                'jp' => $affiliate_data['name']['jp'] ?? "",
            ],
            'description' => [
                'en' => $affiliate_data['description']['en'] ?? "",
                'zh' => $affiliate_data['description']['zh'] ?? "",
                'jp' => $affiliate_data['description']['jp'] ?? "",
            ],
            'link'        => $affiliate_data['link'] ?? "",
        ];

        return static::create($attributes);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function addImage($file)
    {
        $this->clearImage();
        return $this->addMedia($file)
                    ->preservingOriginal()
                    ->toMediaCollection(static::LOGO);
    }

    public function clearImage()
    {
        $this->clearMediaCollection(static::LOGO);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
             ->fit(Manipulations::FIT_CROP, 600, 400)
             ->keepOriginalImageFormat()
             ->optimize()
             ->performOnCollections(static::LOGO);
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

    public function toArray()
    {
        $logo = $this->getFirstMedia(static::LOGO);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'link' => $this->link,
            'logo_original' => $logo ? $logo->getUrl() : static::DEFAULT_IMG,
            'logo_thumb' => $logo ? $logo->getUrl('thumb') : static::DEFAULT_IMG,
            'is_public' => $this->is_public,
        ];
    }

    public function forCurrentLang()
    {
        $locale = app()->getLocale();
        $translated = [
            'name' => $this->name[$locale] ?? '',
            'description' => $this->description[$locale] ?? '',
        ];

        return array_merge($this->toArray(), $translated);
    }
}
