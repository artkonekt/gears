<?php
/**
 * Contains the Backend interface.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-02-26
 *
 */

namespace Konekt\Gears\Contracts;

use Illuminate\Support\Collection;

interface Backend
{
    /**
     * Returns all the saved settings
     *
     * @return Collection
     */
    public function allSettings(): Collection;

    /**
     * Returns all the saved preferences for a user
     *
     * @param int $userId
     *
     * @return Collection
     */
    public function allPreferences($userId): Collection;

    /**
     * Returns the value for a specific setting
     *
     * @param string $key
     * @return mixed
     */
    public function getSetting(string $key);

    /**
     * Returns the value of a specific preference for a user
     *
     * @param string $key
     * @param int    $userId
     *
     * @return mixed
     */
    public function getPreference($key, $userId);

    /**
     * Sets the value for a specific setting
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     */
    public function setSetting($key, $value);

    /**
     * Sets the value for a specific preference
     *
     * @param string $key
     * @param mixed  $value
     * @param int    $userId
     *
     * @return mixed
     */
    public function setPreference($key, $value, $userId);
}
