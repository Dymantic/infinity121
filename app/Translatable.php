<?php


namespace App;


trait Translatable
{
    public function translated($attribute, $lang)
    {
        if(!in_array($attribute, $this->translatable)) {
            return $this->{$attribute};
        }

        $translations = $this->{$attribute};

        return $translations[$lang] ?? null;
    }

    public function updateWithTranslations($data)
    {
        $default_lang = config('app.lang', 'en');

        $translatable_data = collect($data)
            ->filter(function($value, $key) {
                return in_array($key, $this->translatable);
            });

        $non_translatable = collect($data)
            ->reject(function($value, $key) {
                return in_array($key, $this->translatable);
            })->all();

        $translated = $translatable_data->flatMap(function($value, $key) use ($default_lang) {
            if(is_array($value)) {
                return collect($value)->flatMap(function($input, $lang) use ($key) {
                    return ["{$key}->{$lang}" => $input];
                });
            }

            return ["{$key}->{$default_lang}" => $value];
        })->all();

        $this->update($non_translatable);
        $this->update($translated);

    }

    private function getTranslation($field, $desired_lang, $default)
    {
        if (($this->{$field}[$desired_lang] ?? '') === '') {
            return $this->{$field}[$default];
        }

        return $this->{$field}[$desired_lang];
    }
}
