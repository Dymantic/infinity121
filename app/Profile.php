<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Profile extends Model implements HasMedia
{
    use Translatable, HasMediaTrait;

    const AVATAR = 'avatar';

    protected $guarded = [];

    protected $casts = ['bio' => 'array'];

    public $translatable = ['bio'];

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

    public function toArray()
    {
        $avatar = $this->getFirstMedia(static::AVATAR);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'bio' => $this->bio,
            'nationality' => $this->nationality,
            'qualifications' => $this->qualifications,
            'teaching_since' => $this->teaching_since,
            'chinese_ability' => $this->chinese_ability,
            'avatar_original' => $avatar ? $avatar->getUrl() : null,
            'avatar_thumb' => $avatar ? $avatar->getUrl('thumb') : null,
        ];
    }
}
