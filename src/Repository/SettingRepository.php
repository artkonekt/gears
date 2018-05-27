<?php
/**
 * Contains the SettingRepository class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-03-20
 *
 */

namespace Konekt\Gears\Repository;

use Konekt\Gears\Contracts\Backend;
use Konekt\Gears\Exceptions\UnregisteredSettingException;
use Konekt\Gears\Registry\SettingsRegistry;

class SettingRepository
{
    /** @var Backend */
    protected $backend;

    /** @var SettingsRegistry */
    private $registry;

    public function __construct(Backend $backend, SettingsRegistry $registry)
    {
        $this->backend  = $backend;
        $this->registry = $registry;
    }

    public function getRegistry(): SettingsRegistry
    {
        return $this->registry;
    }

    /**
     * Returns the value of a setting
     *
     * @param string $key
     *
     * @return mixed
     * @throws UnregisteredSettingException
     */
    public function get($key)
    {
        $setting = $this->getSettingOrFail($key);

        $value = $this->backend->getSetting($key);

        return  is_null($value) ? $setting->default() : $value;
    }

    /**
     * Updates the value of a setting
     *
     * @param string $key
     * @param mixed  $value
     * @throws UnregisteredSettingException
     */
    public function set($key, $value)
    {
        $this->getSettingOrFail($key);

        $this->backend->setSetting($key, $value);
    }

    /**
     * Deletes the value of a setting
     *
     * @param $key
     * @throws UnregisteredSettingException
     */
    public function forget($key)
    {
        $this->getSettingOrFail($key);

        $this->backend->removeSetting($key);
    }

    /**
     * Returns all the saved setting values as key/value pairs
     *
     * @return array
     */
    public function all()
    {
        return array_merge(
            $this->registry->allDefaults(),
            $this->backend->allSettings()->all()
        );
    }

    /**
     * Update multiple settings at once. It's OK to pass settings that have no values yet
     *
     * @param array $settings Pass key/value pairs
     * @throws UnregisteredSettingException
     */
    public function update(array $settings)
    {
        foreach ($settings as $key => $value) {
            $this->getSettingOrFail($key);
        }

        $this->backend->setSettings($settings);
    }

    /**
     * Reset values of multiple settings at once.
     *
     * @param array $keys Pass an array of keys
     * @throws UnregisteredSettingException
     */
    public function reset(array $keys)
    {
        foreach ($keys as $key) {
            $this->getSettingOrFail($key);
        }

        $this->backend->removeSettings($keys);
    }

    /**
     * Returns the setting registered with the given key and throws and exception if it doesn't exist
     *
     * @param string $key
     *
     * @return \Konekt\Gears\Contracts\Setting
     * @throws UnregisteredSettingException
     */
    protected function getSettingOrFail(string $key)
    {
        if (!$this->registry->has($key)) {
            throw new UnregisteredSettingException(
                sprintf(
                    'There\'s no setting registered with key `%s`',
                    $key
                    )
            );
        }

        return $this->registry->get($key);
    }
}
