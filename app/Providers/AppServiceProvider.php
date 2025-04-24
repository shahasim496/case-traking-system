<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\View\Composers\NotificationComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Routing\UrlGenerator;

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
    public function boot(UrlGenerator $url)
    {
        if (env('APP_ENV') === 'production') {
            $url->forceScheme('https');
        }
        
        // Using class based composers...
        View::composer('layouts.header', NotificationComposer::class);
    }
}
