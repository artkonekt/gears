<?php
/**
 * Contains the Database backend class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-02-26
 *
 */

namespace Konekt\Gears\Backend\Drivers;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Konekt\Gears\Contracts\Backend;

class Database implements Backend
{
    const SETTINGS_TABLE_NAME    = 'settings';
    const PREFERENCES_TABLE_NAME = 'preferences';

    /**
     * @inheritDoc
     */
    public function allSettings(): Collection
    {
        return DB::table(self::SETTINGS_TABLE_NAME)
                 ->select(['id', 'value'])
                 ->get()
                 ->pluck('value', 'id');
    }

    /**
     * @inheritDoc
     */
    public function allPreferences($userId): Collection
    {
        return DB::table(self::PREFERENCES_TABLE_NAME)
                 ->select(['key', 'value'])
                 ->where(['user_id' => $userId])
                 ->get()
                 ->pluck('value', 'key');
    }

    /**
     * @inheritDoc
     */
    public function getSetting(string $key)
    {
        $result = DB::table(self::SETTINGS_TABLE_NAME)
                    ->select('value')
                    ->where('id', $key)
                    ->first();

        return $result ? $result->value : null;
    }

    /**
     * @inheritDoc
     */
    public function getPreference($key, $userId)
    {
        $result = DB::table(self::PREFERENCES_TABLE_NAME)
                    ->select('value')
                    ->where('key', $key)
                    ->where('user_id', $userId)
                    ->first();

        return $result ? $result->value : null;
    }

    /**
     * @inheritDoc
     */
    public function setSetting($key, $value)
    {
        $this->updateOrInsertRecordWithTimestamps(
            self::SETTINGS_TABLE_NAME,
            ['id'    => $key],
            ['value' => $value]
        );
    }

    /**
     * @inheritDoc
     */
    public function setSettings(array $settings)
    {
        DB::transaction(function () use ($settings) {
            foreach ($settings as $key => $value) {
                $this->setSetting($key, $value);
            }
        });
    }

    /**
     * @inheritDoc
     */
    public function setPreferences(array $preferences, $userId)
    {
        DB::transaction(function () use ($preferences, $userId) {
            foreach ($preferences as $key => $value) {
                $this->setPreference($key, $value, $userId);
            }
        });
    }

    /**
     * @inheritDoc
     */
    public function setPreference($key, $value, $userId)
    {
        $this->updateOrInsertRecordWithTimestamps(
            self::PREFERENCES_TABLE_NAME,
            [
                'key'     => $key,
                'user_id' => $userId
            ],
            [
                'value' => $value
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function removeSetting($key)
    {
        DB::table(self::SETTINGS_TABLE_NAME)->where('id', $key)->delete();
    }

    /**
     * @inheritDoc
     */
    public function removePreference($key, $userId)
    {
        DB::table(self::PREFERENCES_TABLE_NAME)
          ->where([
              'key'     => $key,
              'user_id' => $userId
          ])->delete();
    }

    /**
     * @inheritDoc
     */
    public function removeSettings(array $keys)
    {
        DB::transaction(function () use ($keys) {
            foreach ($keys as $key) {
                $this->removeSetting($key);
            }
        });
    }

    /**
     * @inheritDoc
     */
    public function removePreferences(array $keys, $userId)
    {
        DB::transaction(function () use ($keys, $userId) {
            foreach ($keys as $key) {
                $this->removePreference($key, $userId);
            }
        });
    }

    protected function updateOrInsertRecordWithTimestamps($tableName, $lookup, $values)
    {
        $now    = Carbon::now();
        $values = array_merge($values, ['updated_at' => $now]);

        $table = DB::table($tableName);

        if (! $table->where($lookup)->exists()) {
            $table->insert(array_merge($lookup, $values, ['created_at' => $now]));
        } else {
            $table->take(1)->update($values);
        }
    }
}
