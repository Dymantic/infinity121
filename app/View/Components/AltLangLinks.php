<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AltLangLinks extends Component
{

    public function __construct()
    {
        //
    }


    public function render()
    {
        return view('components.alt-lang-links');
    }

    public function alternatives()
    {
        return $this->languages()->reject(fn ($lang) => $lang['locale'] === app()->getLocale());
    }

    private function languages()
    {
        return collect([
            ['locale' => 'en'],
            ['locale' => 'zh'],
            ['locale' => 'jp'],
        ]);
    }
}
