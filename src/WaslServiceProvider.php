<?php

namespace Moltaqa\Wasl;

use Illuminate\Support\ServiceProvider;

class WaslServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/wasl.php' => config_path('wasl.php'),
        ], 'moltaqa-wasl');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/wasl.php',
            'moltaqa-wasl'
        );

        $this->app->bind('moltaqa-wasl', function ($app) {
            return new Wasl();
        });
    }
}
