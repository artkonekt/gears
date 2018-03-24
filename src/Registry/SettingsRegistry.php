<?php
/**
 * Contains the SettingsRegistry class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-03-18
 *
 */

namespace Konekt\Gears\Registry;

use Konekt\Gears\Contracts\Setting;
use Konekt\Gears\Defaults\SimpleSetting;

class SettingsRegistry extends BaseRegistry
{
    public function add(Setting $setting)
    {
        $this->items->put($setting->key(), $setting);
    }

    /**
     * Returns a setting based on its key
     *
     * @param string $key
     *
     * @return Setting|null
     */
    public function get(string $key)
    {
        return parent::get($key);
    }

    /**
     * Adds a setting by passing only the key
     *
     * @param string $key
     *
     * @return Setting Returns the created and registered Setting object
     */
    public function addByKey(string $key): Setting
    {
        $setting = new SimpleSetting($key);

        $this->add($setting);

        return $setting;
    }

    public function remove(Setting $setting)
    {
        $this->removeByKey($setting->key());
    }
}
