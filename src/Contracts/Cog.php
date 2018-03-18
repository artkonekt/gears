<?php
/**
 * Contains the Cog interface.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-03-17
 *
 */

namespace Konekt\Gears\Contracts;

/**
 * Cog is an internal abstract concept: common base for settings and preferences
 */
interface Cog
{
    /**
     * Returns the key (identifier) of the setting
     *
     * @return string
     */
    public function key();

    /**
     * Returns the default value of the setting
     *
     * @return mixed
     */
    public function default();

    /**
     * Returns whether the access to the setting is allowed
     *
     * @return bool
     */
    public function isAllowed();

    /**
     * Returns the available options (if any) for the setting
     *
     * Eg. dropdown values, radio button values, etc
     *
     * @return null|array
     */
    public function options();
}
