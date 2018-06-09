<?php
/**
 * Contains the PreferenceItem class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-04-27
 *
 */

namespace Konekt\Gears\UI;

use Konekt\Gears\Contracts\Preference;
use Konekt\Gears\Enums\CogType;

class PreferenceItem extends BaseItem
{
    public function __construct($widget, Preference $setting, $value = null)
    {
        parent::__construct($widget, $setting, $value);
        $this->type = CogType::PREFERENCE();
    }

    public function getPreference(): Preference
    {
        return $this->getCog();
    }
}
