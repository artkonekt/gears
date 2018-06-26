<?php
/**
 * Contains the SettingsRegistryTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-03-18
 *
 */

namespace Konekt\Gears\Tests;

use Konekt\Gears\Contracts\Setting;
use Konekt\Gears\Defaults\SimpleSetting;
use Konekt\Gears\Registry\SettingsRegistry;
use Konekt\Gears\Tests\Examples\CustomSetting;

class SettingsRegistryTest extends \PHPUnit\Framework\TestCase
{
    /** @var SettingsRegistry */
    private $registry;

    /**
     * @test
     */
    public function settings_can_be_registered_with_an_object()
    {
        $setting = new SimpleSetting('test.key');

        $this->registry->add($setting);
        $this->assertTrue($this->registry->has('test.key'));
        $this->assertCount(1, $this->registry->all());
    }

    /**
     * @test
     */
    public function settings_can_be_registered_with_key()
    {
        $setting = $this->registry->addByKey('hello.fresh');

        $this->assertInstanceOf(Setting::class, $setting);
        $this->assertTrue($this->registry->has('hello.fresh'));
        $this->assertCount(1, $this->registry->all());
    }

    /**
     * @test
     */
    public function settings_can_be_removed_by_object()
    {
        $setting = $this->registry->addByKey('hello.fresh');

        $this->assertInstanceOf(Setting::class, $setting);
        $this->assertTrue($this->registry->has('hello.fresh'));
        $this->assertCount(1, $this->registry->all());

        $this->registry->remove($setting);
        $this->assertFalse($this->registry->has('hello.fresh'));
        $this->assertCount(0, $this->registry->all());
    }

    /**
     * @test
     */
    public function settings_can_be_removed_by_key()
    {
        $setting = new SimpleSetting('another_key');
        $this->registry->add($setting);

        $this->assertInstanceOf(Setting::class, $setting);
        $this->assertTrue($this->registry->has('another_key'));
        $this->assertCount(1, $this->registry->all());

        $this->registry->removeByKey('another_key');
        $this->assertFalse($this->registry->has('another_key'));
        $this->assertCount(0, $this->registry->all());
    }

    /**
     * @test
     */
    public function setting_registered_with_objects_can_be_returned_by_key()
    {
        $addedSetting = new SimpleSetting('velo.drom');
        $this->registry->add($addedSetting);

        $returnedSetting = $this->registry->get('velo.drom');

        $this->assertInstanceOf(Setting::class, $returnedSetting);
        $this->assertEquals($addedSetting->key(), $returnedSetting->key());
    }

    /**
     * @test
     */
    public function setting_registered_with_keys_can_be_returned_by_key()
    {
        $this->registry->addByKey('storkower');

        $returnedSetting = $this->registry->get('storkower');

        $this->assertInstanceOf(Setting::class, $returnedSetting);
        $this->assertEquals('storkower', $returnedSetting->key());
    }

    /**
     * @test
     */
    public function custom_setting_class_can_be_registered()
    {
        $customSetting = new CustomSetting();
        $this->registry->add($customSetting);

        $returnedSetting = $this->registry->get('custom.setting');

        $this->assertInstanceOf(Setting::class, $returnedSetting);
        $this->assertInstanceOf(CustomSetting::class, $returnedSetting);
        $this->assertEquals('Default', $returnedSetting->default());
        $this->assertEquals('custom.setting', $returnedSetting->key());

    }

    protected function setUp()
    {
        parent::setUp();

        $this->registry = new SettingsRegistry();
    }
}
