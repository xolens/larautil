<?php

namespace Xolens\Larautil;

use Illuminate\Support\ServiceProvider;

class LarautilServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/larautil.php' => config_path('larautil.php'),
        ]);
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/larautil.php', 'larautil'
        );
    }
}