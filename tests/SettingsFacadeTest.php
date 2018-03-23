<?php
/**
 * Contains the SettingsFacadeTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-03-23
 *
 */

namespace Konekt\Gears\Tests;

use Konekt\Gears\Facades\Settings;
use Konekt\Gears\Registry\SettingsRegistry;
use Konekt\Gears\Repository\SettingRepository;

class SettingsFacadeTest extends TestCase
{
    /**
     * @test
     */
    public function the_settings_facade_gives_a_setting_repository_instance()
    {
        $this->assertInstanceOf(SettingRepository::class, Settings::getFacadeRoot());
    }

    /**
     * @test
     */
    public function the_settings_facade_uses_the_common_settings_registry()
    {
        /** @var SettingsRegistry $registry */
        $registry = $this->app->get('gears.settings_registry');

        $registry->addByKey('hello');
        Settings::set('hello', 'world');
        $this->assertEquals('world', Settings::get('hello'));

        $this->app->get('gears.settings_registry')->addByKey('ola');
        Settings::set('ola', 'mundo');
        $this->assertEquals('mundo', Settings::get('ola'));
    }

    /**
     * @test
     */
    public function the_settings_facade_provides_access_to_all_the_methods_of_settings_repository()
    {
        $this->app->get('gears.settings_registry')->addByKey('magento');

        Settings::set('magento', 'ecommerce');
        $this->assertEquals('ecommerce', Settings::get('magento'));

        Settings::forget('magento');
        $this->assertNull(Settings::get('magento'));

        Settings::update(['magento' => 'Zend']);
        $this->assertArrayHasKey('magento', Settings::all());
        $this->assertEquals('Zend', Settings::all()['magento']);

        Settings::delete(['magento']);
        $this->assertArrayHasKey('magento', Settings::all());
        $this->assertNull(Settings::all()['magento']);
    }
}
