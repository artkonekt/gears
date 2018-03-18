<?php
/**
 * Contains the GearsServiceProvider class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-03-17
 *
 */

namespace Konekt\Gears\Providers;

use Illuminate\Support\ServiceProvider;

class GearsServiceProvider extends ServiceProvider
{
    const DEFAULT_BACKEND_DRIVER = 'database';

    public function register()
    {
        $this->loadMigrationsFrom(dirname(__DIR__) . '/resources/database/');

//        $this->app->singleton('gears', function() {
//            return new Gears(
//                BackendFactory::create($this->config('driver', self::DEFAULT_BACKEND_DRIVER))
//            );
//        });

//        $this->app->singleton('gears.settings', function($app) {
//            return $app['gears']->settings();
//        });
    }

    private function config($key, $default = null)
    {
        return config('gears.' . $key, $default);
    }

}
