<?php


namespace App;


class Nationalities
{
    const LIST = [
        'zaf' => ['en' => 'South African', 'zh' => '南非的', 'jp' => '南アフリカ人'],
        'twn' => ['en' => 'Taiwanese', 'zh' => '台灣', 'jp' => '台湾人'],
    ];

    public static function all()
    {
        return static::LIST;
    }

    public static function forLang($lang)
    {
        return collect(static::LIST)
            ->mapWithKeys(function($names, $code) use ($lang) {
                return [$code => $names[$lang] ?? $names['en']];
            })->all();
    }

    public static function byCode($code)
    {
        return static::LIST[$code] ?? [];
    }
}
