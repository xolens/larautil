<?php

namespace Xolens\PgLarautil;

use Illuminate\Support\ServiceProvider;

class PgLarautilServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/pglarautil.php' => config_path('pglarautil.php'),
        ]);
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/pglarautil.php', 'pglarautil'
        );
    }
}