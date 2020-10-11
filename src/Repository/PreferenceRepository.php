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

    public function getRegistry(): PreferencesRegistry
    {
        return $this->registry;
    }

    /**
     * Returns the value of a preference
     *
     * @param string                   $key
     * @param int|Authenticatable|null $user
     *
     * @throws UnregisteredPreferenceException
     * @return mixed
     */
    public function get($key, $user = null)
    {
        $preference = $this->getPreferenceOrFail($key);

        $value = $this->backend->getPreference($key, $this->getUserId($user));

        return  is_null($value) ? $preference->default() : $value;
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
        $this->getPreferenceOrFail($key);

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
        $this->getPreferenceOrFail($key);

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
        return array_merge(
            $this->registry->allDefaults(),
            $this->backend->allPreferences($this->getUserId($user))->all()
        );
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
            $this->getPreferenceOrFail($key);
        }

        $this->backend->setPreferences($preferences, $this->getUserId($user));
    }

    /**
     * Reset values of multiple preferences at once.
     *
     * @param array $keys Pass an array of keys
     * @param int|Authenticatable|null $user
     * @throws UnregisteredPreferenceException
     */
    public function reset(array $keys, $user = null)
    {
        foreach ($keys as $key) {
            $this->getPreferenceOrFail($key);
        }

        $this->backend->removePreferences($keys, $this->getUserId($user));
    }

    protected function getUserId($user)
    {
        if (is_null($user)) {
            return Auth::user() ? Auth::user()->id : null;
        } elseif (is_object($user)) {
            return $user->id;
        }

        return $user;
    }

    /**
     * Returns the preference registered with the given key and throws an exception if not found
     *
     * @param string $key
     *
     * @throws UnregisteredPreferenceException
     * @return \Konekt\Gears\Contracts\Preference
     */
    protected function getPreferenceOrFail(string $key)
    {
        if (!$this->registry->has($key)) {
            throw new UnregisteredPreferenceException(
                sprintf(
                    'There\'s no setting registered with key `%s`',
                    $key
                )
            );
        }

        return $this->registry->get($key);
    }
}
