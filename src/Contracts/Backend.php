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
     */
    public function setSetting($key, $value);

    /**
     * Sets the value for a specific preference
     *
     * @param string $key
     * @param mixed  $value
     * @param int    $userId
     */
    public function setPreference($key, $value, $userId);

    /**
     * Set the values of multiple settings at once
     *
     * @param array $settings Pass key/value pairs
     */
    public function setSettings(array $settings);

    /**
     * Set the values of multiple preferences for a single user at once
     *
     * @param array $preferences Pass key/value pairs
     * @param int   $userId
     */
    public function setPreferences(array $preferences, $userId);

    /**
     * Deletes the value for a specific setting
     *
     * @param string $key
     */
    public function removeSetting($key);

    /**
     * Deletes the value for a specific preference
     *
     * @param string $key
     * @param int    $userId
     */
    public function removePreference($key, $userId);

    /**
     * Deletes values of multiple settings at once
     *
     * @param array $keys
     */
    public function removeSettings(array $keys);

    /**
     * Deletes values of multiple preferences for a single user at once
     *
     * @param array $keys
     * @param int   $userId
     */
    public function removePreferences(array $keys, $userId);
}
