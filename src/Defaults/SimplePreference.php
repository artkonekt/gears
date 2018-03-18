<?php
/**
 * Contains the SimplePreference class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-03-18
 *
 */

namespace Konekt\Gears\Defaults;

use Konekt\Gears\Contracts\Preference;
use Konekt\Gears\Traits\SimpleCog;

class SimplePreference implements Preference
{
    use SimpleCog;

    public function __construct(string $key, $default = null, $options = null)
    {
        $this->key            = $key;
        $this->default        = $default;
        $this->options        = $options;
    }
}
