<?php

namespace TeleStepHandler;

use Illuminate\Support\ServiceProvider;

class TeleStepHandlerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/tele_steps.php' => config_path('tele_steps.php'),
        ], 'config');
    }
}
