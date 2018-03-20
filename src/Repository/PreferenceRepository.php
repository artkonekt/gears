<?php
/**
 * Contains the PreferenceRepository class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-03-20
 *
 */

namespace Konekt\Gears\Repository;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Konekt\Gears\Contracts\Backend;
use Konekt\Gears\Exceptions\UnregisteredPreferenceException;
use Konekt\Gears\Registry\PreferencesRegistry;

class PreferenceRepository
{
    /** @var Backend */
    protected $backend;

    /** @var PreferencesRegistry */
    private $registry;

    public function __construct(Backend $backend, PreferencesRegistry $registry)
    {
        $this->backend  = $backend;
        $this->registry = $registry;
    }

    /**
     * Returns the value of a preference
     *
     * @param string                   $key
     * @param int|Authenticatable|null $user
     *
     * @return mixed
     * @throws UnregisteredPreferenceException
     */
    public function get($key, $user = null)
    {
        $this->verifyOrFail($key);

        return $this->backend->getPreference($key, $this->getUserId($user));
    }

    /**
     * Updates the value of a preference
     *
     * @param string                   $key
     * @param mixed                    $value
     * @param int|Authenticatable|null $user
     * @throws UnregisteredPreferenceException
     */
    public function set($key, $value, $user = null)
    {
        $this->verifyOrFail($key);

        $this->backend->setPreference($key, $value, $this->getUserId($user));
    }

    /**
     * Deletes the value of a preference
     *
     * @param string                   $key
     * @param int|Authenticatable|null $user
     * @throws UnregisteredPreferenceException
     */
    public function forget($key, $user = null)
    {
        $this->verifyOrFail($key);

        $this->backend->removePreference($key, $this->getUserId($user));
    }

    /**
     * Returns all the saved preference values for a given user as key/value pairs
     *
     * @param int|Authenticatable|null $user
     *
     * @return array
     */
    public function all($user = null)
    {
        return $this->backend
            ->allPreferences($this->getUserId($user))
            ->all();
    }

    /**
     * Update multiple preferences at once. It's OK to pass preferences that have no saved values yet
     *
     * @param array $preferences Pass key/value pairs
     * @param int|Authenticatable|null $user
     * @throws UnregisteredPreferenceException
     */
    public function update(array $preferences, $user = null)
    {
        foreach ($preferences as $key => $value) {
            $this->verifyOrFail($key);
        }

        $this->backend->setPreferences($preferences, $this->getUserId($user));
    }

    /**
     * Delete values of multiple preferences at once.
     *
     * @param array $keys Pass an array of keys
     * @param int|Authenticatable|null $user
     * @throws UnregisteredPreferenceException
     */
    public function delete(array $keys, $user = null)
    {
        foreach ($keys as $key) {
            $this->verifyOrFail($key);
        }

        $this->backend->removePreferences($keys, $this->getUserId($user));
    }

    protected function getUserId($user)
    {
        if (is_null($user)) {
            return Auth::user()->id;
        } elseif (is_object($user)) {
            return $user->id;
        }

        return $user;
    }

    /**
     * Checks if preferemce with the given key was registered and throws an exception if not
     *
     * @param string $key
     * @throws UnregisteredPreferenceException
     */
    protected function verifyOrFail(string $key)
    {
        if (!$this->registry->has($key)) {
            throw new UnregisteredPreferenceException(
                sprintf(
                    'There\'s no setting registered with key `%s`',
                    $key
                )
            );
        }
    }

}
