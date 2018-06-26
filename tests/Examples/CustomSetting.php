<?php
/**
 * Contains the CustomSetting class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-06-26
 *
 */

namespace Konekt\Gears\Tests\Examples;

use Konekt\Gears\Contracts\Setting;

class CustomSetting implements Setting
{
    /**
     * @inheritDoc
     */
    public function key()
    {
        return 'custom.setting';
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

    /**
     * @inheritDoc
     */
    public function syncWithConfig()
    {
        return false;
    }
}
