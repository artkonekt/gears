<?php
/**
 * Contains the WidgetTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-04-09
 *
 */

namespace Konekt\Gears\Tests;

use Konekt\Gears\UI\Widget;

class WidgetTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function widget_has_a_component_and_attributes()
    {
        $widget = new Widget('text');

        $this->assertEquals('text', $widget->component());
        $this->assertInternalType('array', $widget->attributes());
    }

    /**
     * @test
     */
    public function widget_attributes_can_be_set_in_constructor()
    {
        $widget = new Widget('text', ['class' => 'input-lg']);

        $this->assertEquals('input-lg', $widget->getAttribute('class'));
    }

    /**
     * @test
     */
    public function widget_attributes_can_be_set_explicitly()
    {
        $widget = new Widget('text');
        $widget->setAttribute('readonly', true);

        $this->assertEquals(true, $widget->getAttribute('readonly'));
    }

    /**
     * @test
     */
    public function all_attributes_can_be_returned()
    {
        $widget = new Widget('checkbox', ['class' => 'checkbox-primary']);
        $widget->setAttribute('readonly', 1);

        $attrs = $widget->attributes();
        $this->assertEquals('checkbox-primary', $attrs['class']);
        $this->assertEquals(1, $attrs['readonly']);
    }
}
