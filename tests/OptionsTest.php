<?php
/**
 * Contains the OptionsTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-11-01
 *
 */

namespace Konekt\Gears\Tests;

use Konekt\Gears\Defaults\SimplePreference;
use Konekt\Gears\Defaults\SimpleSetting;

class OptionsTest extends TestCase
{
    /** @test */
    public function passing_an_array_as_options_in_the_simple_setting_constructor_returns_the_same_array_when_calling_options_method()
    {
        $setting = new SimpleSetting('my', null, ['4', '5', '6']);

        $this->assertEquals(['4', '5', '6'], $setting->options());
    }

    /** @test */
    public function passing_a_callback_as_options_in_the_simple_setting_constructor_returns_the_result_of_the_callback_when_calling_options_method()
    {
        $setting = new SimpleSetting('mine', null, function () {
            return ['7', '8', '9'];
        });

        $this->assertEquals(['7', '8', '9'], $setting->options());
    }

    /** @test */
    public function passing_an_array_as_options_in_the_simple_preference_constructor_returns_the_same_array_when_calling_options_method()
    {
        $preference = new SimplePreference('users.favourite', 'b', ['a', 'b', 'c']);

        $this->assertEquals(['a', 'b', 'c'], $preference->options());
    }

    /** @test */
    public function passing_a_callback_as_options_in_the_simple_preference_constructor_returns_the_result_of_the_callback_when_calling_options_method()
    {
        $preference = new SimplePreference('users.bestof', null, function () {
            return ['x', 'y', 'z'];
        });

        $this->assertEquals(['x', 'y', 'z'], $preference->options());
    }
}
