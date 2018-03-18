<?php
/**
 * Contains the Setting interface.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-02-25
 *
 */

namespace Konekt\Gears\Contracts;

interface Setting extends Cog
{
    /**
     * Returns whether or not the setting should be synchronized with
     * configuration (Laravel's built in facility).
     *
     * Synchronization means, the setting's value will be set as config value
     * using the same key. This assignment is runtime only, values are not
     * being saved to configuration files, but resumed from the setting
     *
     * @return bool
     */
    public function syncWithConfig();
}
