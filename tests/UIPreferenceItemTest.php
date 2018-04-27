<?php
/**
 * Contains the UIPreferenceItemTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-04-27
 *
 */

namespace Konekt\Gears\Tests;

use Konekt\Gears\Contracts\Preference;
use Konekt\Gears\Defaults\SimplePreference;
use Konekt\Gears\UI\PreferenceItem;
use Konekt\Gears\UI\Widget;

class UIPreferenceItemTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function it_has_a_widget_and_a_cog()
    {
        $item = new PreferenceItem(new Widget('text'), new SimplePreference('preference_key'));

        $this->assertInstanceOf(Widget::class, $item->getWidget());
        $this->assertInstanceOf(Preference::class, $item->getPreference());
    }

    /**
     * @test
     */
    public function it_can_be_instantiated_with_widget_component_name_only()
    {
        $item = new PreferenceItem('text', new SimplePreference('preference_key'));

        $widget = $item->getWidget();
        $this->assertInstanceOf(Widget::class, $widget);
        $this->assertEquals('text', $widget->component());
    }

    /**
     * @test
     */
    public function it_can_be_instantiated_with_array_containing_component_name_and_widget_attributes()
    {
        $item = new PreferenceItem(['text', ['readonly' => true, 'class' => 'input-lg']], new SimplePreference('preference_key'));

        $widget = $item->getWidget();
        $this->assertInstanceOf(Widget::class, $widget);
        $this->assertEquals('text', $widget->component());
        $this->assertEquals(true, $widget->getAttribute('readonly'));
        $this->assertEquals('input-lg', $widget->getAttribute('class'));
    }

    /**
     * @test
     */
    public function it_returns_the_preference()
    {
        $item = new PreferenceItem('text', new SimplePreference('preference_key'));

        $this->assertInstanceOf(Preference::class, $item->getPreference());
    }

    /**
     * @test
     */
    public function it_has_a_value()
    {
        $item = new PreferenceItem('text', new SimplePreference('color_scheme'), 'green');

        $this->assertEquals('green', $item->getValue());
    }

    /**
     * @test
     */
    public function returns_the_default_value_if_value_has_not_been_explicitly_set()
    {
        $item = new PreferenceItem('text', new SimplePreference('theme', 'dark'));

        $this->assertEquals('dark', $item->getValue());
    }
}
