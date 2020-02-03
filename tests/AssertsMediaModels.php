<?php


namespace Tests;


use Illuminate\Support\Str;

trait AssertsMediaModels
{
    public function getImageDiskPath($image)
    {
        return Str::replaceFirst("/media/", "", $image->getUrl());
    }

    public function getImageConversionDiskPaths($image, $conversions)
    {
        return collect($conversions)->map(function($conversion) use ($image) {
            return Str::replaceFirst("/media/", "", $image->getUrl($conversion));
        })->all();
    }
}
