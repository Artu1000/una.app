<?php

namespace App\Providers;

use App\Composers\Modal\ModalComposer;
use App\Composers\Pages\PagesComposer;
use App\Composers\Partner\PartnerComposer;
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
        view()->composer('templates.front.partials.header', PagesComposer::class);
        view()->composer('templates.front.partials.partners', PartnerComposer::class);
        view()->composer('*', ModalComposer::class);
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
