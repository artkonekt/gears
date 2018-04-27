<?php
/**
 * Contains the SettingItem class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-04-27
 *
 */

namespace Konekt\Gears\UI;

use Konekt\Gears\Contracts\Setting;

class SettingItem extends BaseItem
{
    public function __construct($widget, Setting $setting, $value = null)
    {
        parent::__construct($widget, $setting, $value);
    }

    public function getSetting(): Setting
    {
        return $this->getCog();
    }
}
