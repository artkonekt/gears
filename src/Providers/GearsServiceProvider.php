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
use Konekt\Gears\Backend\BackendFactory;
use Konekt\Gears\Registry\PreferencesRegistry;
use Konekt\Gears\Registry\SettingsRegistry;
use Konekt\Gears\Repository\PreferenceRepository;
use Konekt\Gears\Repository\SettingRepository;

class GearsServiceProvider extends ServiceProvider
{
    const DEFAULT_BACKEND_DRIVER = 'cached_database';

    public function register()
    {
        if ($this->config('migrations', true)) {
            $this->loadMigrationsFrom(dirname(__DIR__) . '/resources/database/');
        }

        $this->publishes([
            dirname(__DIR__) . '/resources/config/gears.php' => config_path('gears.php')
        ], 'config');

        $this->publishes([
            dirname(__DIR__) . '/resources/database' => database_path('migrations')
        ], 'migrations');

        $this->app->singleton('gears.backend', function () {
            return BackendFactory::create($this->config('driver', self::DEFAULT_BACKEND_DRIVER));
        });

        $this->app->singleton('gears.settings_registry', function () {
            return new SettingsRegistry();
        });

        $this->app->singleton('gears.preferences_registry', function () {
            return new PreferencesRegistry();
        });

        $this->app->singleton('gears.settings', function ($app) {
            return new SettingRepository($app['gears.backend'], $app['gears.settings_registry']);
        });

        $this->app->singleton('gears.preferences', function ($app) {
            return new PreferenceRepository($app['gears.backend'], $app['gears.preferences_registry']);
        });
    }

    private function config($key, $default = null)
    {
        return config('gears.' . $key, $default);
    }
}
