<?php
/**
 * Contains the CachedDatabase class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-03-24
 *
 */

namespace Konekt\Gears\Backend\Drivers;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Collection;
use Konekt\Gears\Contracts\Backend;

class CachedDatabase implements Backend
{
    const SETTINGS_KEY_PREFIX    = 'settings::';
    const PREFERENCES_KEY_PREFIX = 'preferences::of';
    const TTL                    = 43800; // 1 month in minutes

    /** @var Database */
    protected $db;

    /** @var Repository */
    protected $cache;

    public function __construct(Repository $cache, Database $db)
    {
        $this->db    = $db;
        $this->cache = $cache;
    }

    /**
     * @inheritDoc
     */
    public function allSettings(): Collection
    {
        return $this->cache->remember($this->skey('*'), self::TTL, function () {
            return $this->db->allSettings();
        });
    }

    /**
     * @inheritDoc
     */
    public function allPreferences($userId): Collection
    {
        return $this->cache->remember($this->pkey('*', $userId), self::TTL, function () use ($userId) {
            return $this->db->allPreferences($userId);
        });
    }

    /**
     * @inheritDoc
     */
    public function getSetting(string $key)
    {
        return $this->cache->remember($this->skey($key), self::TTL, function () use ($key) {
            return $this->db->getSetting($key);
        });
    }

    /**
     * @inheritDoc
     */
    public function getPreference($key, $userId)
    {
        return $this->cache->remember($this->pkey($key, $userId), self::TTL, function () use ($key, $userId) {
            return $this->db->getPreference($key, $userId);
        });
    }

    /**
     * @inheritDoc
     */
    public function setSetting($key, $value)
    {
        $this->sforget($key);
        $this->db->setSetting($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function setPreference($key, $value, $userId)
    {
        $this->pforget($key, $userId);
        $this->db->setPreference($key, $value, $userId);
    }

    /**
     * @inheritDoc
     */
    public function setSettings(array $settings)
    {
        $this->sforget(array_keys($settings));
        $this->db->setSettings($settings);
    }

    /**
     * @inheritDoc
     */
    public function setPreferences(array $preferences, $userId)
    {
        $this->pforget(array_keys($preferences), $userId);
        $this->db->setPreferences($preferences, $userId);
    }

    /**
     * @inheritDoc
     */
    public function removeSetting($key)
    {
        $this->sforget($key);
        $this->db->removeSetting($key);
    }

    /**
     * @inheritDoc
     */
    public function removePreference($key, $userId)
    {
        $this->pforget($key, $userId);
        $this->db->removePreference($key, $userId);
    }

    /**
     * @inheritDoc
     */
    public function removeSettings(array $keys)
    {
        $this->sforget($keys);
        $this->db->removeSettings($keys);
    }

    /**
     * @inheritDoc
     */
    public function removePreferences(array $keys, $userId)
    {
        $this->pforget($keys, $userId);
        $this->db->removePreferences($keys, $userId);
    }

    /**
     * Returns the cache key for a setting
     *
     * @param string $key
     *
     * @return string
     */
    protected function skey(string $key): string
    {
        return self::SETTINGS_KEY_PREFIX . $key;
    }

    /**
     * Returns the cache key for a preference for user
     *
     * @param string $key
     * @param int    $userId
     *
     * @return string
     */
    protected function pkey(string $key, $userId): string
    {
        return sprintf('%s(%s).%s', self::PREFERENCES_KEY_PREFIX, $userId, $key);
    }

    /**
     * Removes a setting from the cache by its key
     *
     * @param string|array $key One or more keys
     */
    protected function sforget($key)
    {
        $keys = is_array($key) ? $key : [$key];

        foreach ($keys as $key) {
            $this->cache->forget($this->skey($key));
        }

        $this->cache->forget($this->skey('*'));
    }

    /**
     * Removes a preference from the cache by key and user
     *
     * @param string|array $key One or more keys
     * @param int          $userId
     */
    protected function pforget($key, $userId)
    {
        $keys = is_array($key) ? $key : [$key];

        foreach ($keys as $key) {
            $this->cache->forget($this->pkey($key, $userId));
        }

        $this->cache->forget($this->pkey('*', $userId));
    }
}
