<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('templates.front.partials.partners', 'App\Composers\Partner\PartnerComposer');
        view()->composer([
            'templates.front.full_layout',
            'templates.front.nude_layout',
            'templates.back.full_layout'
        ], 'App\Composers\Modal\ModalComposer');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
