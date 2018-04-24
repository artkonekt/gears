<?php
/**
 * Contains the UIItemTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-04-24
 *
 */

namespace Konekt\Gears\Tests;

use Konekt\Gears\Contracts\Cog;
use Konekt\Gears\Contracts\Preference;
use Konekt\Gears\Contracts\Setting;
use Konekt\Gears\Defaults\SimplePreference;
use Konekt\Gears\Defaults\SimpleSetting;
use Konekt\Gears\UI\Item;
use Konekt\Gears\UI\Widget;

class UIItemTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function it_has_a_widget_and_a_cog()
    {
        $item = new Item(new Widget('text'), new SimpleSetting('setting_key'));

        $this->assertInstanceOf(Widget::class, $item->getWidget());
        $this->assertInstanceOf(Cog::class, $item->getCog());
    }

    /**
     * @test
     */
    public function it_can_be_instantiated_with_widget_component_name_only()
    {
        $item = new Item('text', new SimpleSetting('setting_key'));

        $widget = $item->getWidget();
        $this->assertInstanceOf(Widget::class, $widget);
        $this->assertEquals('text', $widget->component());
    }

    /**
     * @test
     */
    public function it_can_be_instantiated_with_array_containing_component_name_and_widget_attributes()
    {
        $item = new Item(['text', ['readonly' => true, 'class' => 'input-lg']], new SimpleSetting('setting_key'));

        $widget = $item->getWidget();
        $this->assertInstanceOf(Widget::class, $widget);
        $this->assertEquals('text', $widget->component());
        $this->assertEquals(true, $widget->getAttribute('readonly'));
        $this->assertEquals('input-lg', $widget->getAttribute('class'));
    }

    /**
     * @test
     */
    public function it_returns_the_setting_if_it_has_one()
    {
        $itemWithSetting    = new Item('text', new SimpleSetting('setting_key'));
        $itemWithPreference = new Item('text', new SimplePreference('setting_key'));

        $this->assertInstanceOf(Setting::class, $itemWithSetting->getSetting());
        $this->assertNull($itemWithPreference->getSetting());
    }

    /**
     * @test
     */
    public function it_returns_the_preference_if_it_has_one()
    {
        $itemWithSetting    = new Item('text', new SimpleSetting('setting_key'));
        $itemWithPreference = new Item('text', new SimplePreference('setting_key'));

        $this->assertInstanceOf(Preference::class, $itemWithPreference->getPreference());
        $this->assertNull($itemWithSetting->getPreference());
    }
}
