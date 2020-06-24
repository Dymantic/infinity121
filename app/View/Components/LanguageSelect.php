<?php

namespace App\View\Components;

use Illuminate\View\Component;

class LanguageSelect extends Component
{

    public $light;

    public function __construct($light)
    {
        $this->light = $light;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.language-select');
    }

    public function icon_colour()
    {
        return $this->light ? 'text-hms-navy' : 'text-white';
    }

    public function current()
    {
        return $this->languages()->first(fn ($lang) => $lang['locale'] === app()->getLocale());
    }

    public function translations()
    {
        return $this->languages()->reject(fn ($lang) => $lang['locale'] === app()->getLocale());
    }

    private function languages()
    {
        return collect([
            ['locale' => 'en', 'name' => 'Eng'],
            ['locale' => 'zh', 'name' => '中文'],
            ['locale' => 'jp', 'name' => '日本語'],
        ]);
    }
}
