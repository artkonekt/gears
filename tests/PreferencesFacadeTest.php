<?php
/**
 * Contains the PreferencesFacadeTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-03-23
 *
 */

namespace Konekt\Gears\Tests;

use Konekt\Gears\Facades\Preferences;
use Konekt\Gears\Registry\PreferencesRegistry;
use Konekt\Gears\Repository\PreferenceRepository;

class PreferencesFacadeTest extends TestCase
{
    /**
     * @test
     */
    public function the_preferences_facade_gives_a_preference_repository_instance()
    {
        $this->assertInstanceOf(PreferenceRepository::class, Preferences::getFacadeRoot());
    }

    /**
     * @test
     */
    public function the_preferences_facade_uses_the_common_preferences_registry()
    {
        $uid = 1127;
        /** @var PreferencesRegistry $registry */
        $registry = $this->app->make('gears.preferences_registry');

        $registry->addByKey('fast_food');
        Preferences::set('fast_food', 'kebab', $uid);
        $this->assertEquals('kebab', Preferences::get('fast_food', $uid));

        $this->app->make('gears.preferences_registry')->addByKey('slow_food');
        Preferences::set('slow_food', 'goulash stew', $uid);
        $this->assertEquals('goulash stew', Preferences::get('slow_food', $uid));
    }

    /**
     * @test
     */
    public function the_settings_facade_provides_access_to_all_the_methods_of_settings_repository()
    {
        $mario = 1981;
        $luigi = 1983;

        $this->app->make('gears.preferences_registry')->addByKey('favorite_platform');

        Preferences::set('favorite_platform', 'stripe', $mario);
        Preferences::set('favorite_platform', 'braintree', $luigi);
        $this->assertEquals('stripe', Preferences::get('favorite_platform', $mario));
        $this->assertEquals('braintree', Preferences::get('favorite_platform', $luigi));

        Preferences::forget('favorite_platform', $mario);
        $this->assertNull(Preferences::get('favorite_platform', $mario));

        // Luigi's setting still has to be there:
        $this->assertEquals('braintree', Preferences::get('favorite_platform', $luigi));
        Preferences::forget('favorite_platform', $luigi);
        $this->assertNull(Preferences::get('favorite_platform', $luigi));

        // Mario changes his mind
        Preferences::update(['favorite_platform' => 'paypal'], $mario);
        $this->assertArrayHasKey('favorite_platform', Preferences::all($mario));
        $this->assertEquals('paypal', Preferences::all($mario)['favorite_platform']);

        Preferences::delete(['favorite_platform'], $mario);
        $this->assertArrayHasKey('favorite_platform', Preferences::all($mario));
        $this->assertNull(Preferences::all($mario)['favorite_platform']);
    }
}
