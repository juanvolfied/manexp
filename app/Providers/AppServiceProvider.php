<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Channels\NullChannel;
use Illuminate\Pagination\Paginator;

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
        Carbon::setLocale('es');
    Notification::extend('null', function ($app) {
        return new NullChannel();
    });
        //
            Paginator::useBootstrap(); // esto obliga a usar estilo Bootstrap

    }
}
