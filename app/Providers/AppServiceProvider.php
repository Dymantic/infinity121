<?php

namespace App\Providers;

use App\Teaching\Subject;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(
            'front.partials.footer',
            fn($view) => $view->with('top_subjects', Subject::inOrder()->public()->limit(3)->get()->map->forCurrentLang()));
    }
}
