<?php

namespace Moltaqa\Wasl;

use Illuminate\Support\ServiceProvider;

class WaslServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/lang/vendor/wasl', 'moltaqa-wasl');

        $this->publishes([
            __DIR__ . '/config/wasl.php' => config_path('wasl.php'),
            __DIR__.'/lang' => $this->app->langPath('vendor/wasl'),
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
