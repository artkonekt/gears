<?php
/**
 * Contains the UISettingItemTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-04-24
 *
 */

namespace Konekt\Gears\Tests;

use Konekt\Gears\Contracts\Setting;
use Konekt\Gears\Defaults\SimpleSetting;
use Konekt\Gears\UI\SettingItem;
use Konekt\Gears\UI\Widget;

class UISettingItemTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function it_has_a_widget_and_a_cog()
    {
        $item = new SettingItem(new Widget('text'), new SimpleSetting('setting_key'));

        $this->assertInstanceOf(Widget::class, $item->getWidget());
        $this->assertInstanceOf(Setting::class, $item->getSetting());
    }

    /**
     * @test
     */
    public function it_can_be_instantiated_with_widget_component_name_only()
    {
        $item = new SettingItem('text', new SimpleSetting('setting_key'));

        $widget = $item->getWidget();
        $this->assertInstanceOf(Widget::class, $widget);
        $this->assertEquals('text', $widget->component());
    }

    /**
     * @test
     */
    public function it_can_be_instantiated_with_array_containing_component_name_and_widget_attributes()
    {
        $item = new SettingItem(['text', ['readonly' => true, 'class' => 'input-lg']], new SimpleSetting('setting_key'));

        $widget = $item->getWidget();
        $this->assertInstanceOf(Widget::class, $widget);
        $this->assertEquals('text', $widget->component());
        $this->assertEquals(true, $widget->getAttribute('readonly'));
        $this->assertEquals('input-lg', $widget->getAttribute('class'));
    }

    /**
     * @test
     */
    public function it_returns_the_setting()
    {
        $item = new SettingItem('text', new SimpleSetting('setting_key'));

        $this->assertInstanceOf(Setting::class, $item->getSetting());
    }

    /**
     * @test
     */
    public function it_has_a_value()
    {
        $item = new SettingItem('text', new SimpleSetting('api_key'), 'fedbca9876543210');

        $this->assertEquals('fedbca9876543210', $item->getValue());
    }

    /**
     * @test
     */
    public function returns_the_default_value_if_value_has_not_been_explicitly_set()
    {
        $item = new SettingItem('checkbox', new SimpleSetting('use_https', true));

        $this->assertEquals(true, $item->getValue());
    }
}
