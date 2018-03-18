<?php
/**
 * Contains the PreferencesRegistry class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-03-18
 *
 */

namespace Konekt\Gears\Registry;

use Konekt\Gears\Contracts\Preference;
use Konekt\Gears\Defaults\SimplePreference;

class PreferencesRegistry extends BaseRegistry
{
    public function add(Preference $preference)
    {
        $this->items->put($preference->key(), $preference);
    }

    /**
     * Returns a preference based on its key
     *
     * @param string $key
     *
     * @return Preference|null
     */
    public function get($key)
    {
        return parent::get($key);
    }

    /**
     * Adds a setting by passing only the key
     *
     * @param string $key
     *
     * @return Preference Returns the created and registered Setting object
     */
    function addByKey(string $key): Preference
    {
        $setting = new SimplePreference($key);

        $this->add($setting);

        return $setting;
    }

    public function remove(Preference $setting)
    {
        $this->removeByKey($setting->key());
    }
}
