<?php

/**
 * Description of HutkoServiceProvider.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App;

use Illuminate\Support\ServiceProvider;

class HutkoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/hutko.php',
            'hutko'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/hutko.php' => config_path('hutko.php'),
        ], 'config');
    }
}
