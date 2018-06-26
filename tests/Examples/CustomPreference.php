<?php
/**
 * Contains the CustomPreference class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-06-26
 *
 */

namespace Konekt\Gears\Tests\Examples;

use Konekt\Gears\Contracts\Preference;

class CustomPreference implements Preference
{
    /**
     * @inheritDoc
     */
    public function key()
    {
        return 'custom.preference';
    }

    /**
     * @inheritDoc
     */
    public function default()
    {
        return 'Default';
    }

    /**
     * @inheritDoc
     */
    public function isAllowed()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function options()
    {
        return ['Default', 'Custom'];
    }
}
