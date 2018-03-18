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
        $lookup = [
            'id' => $key
        ];

        DB::table(self::SETTINGS_TABLE_NAME)->updateOrInsert($lookup, [
            'value' => $value
        ]);
    }

    /**
     * @inheritDoc
     */
    public function setPreference($key, $value, $userId)
    {
        $lookup = [
            'key'     => $key,
            'user_id' => $userId
        ];

        DB::table(self::PREFERENCES_TABLE_NAME)->updateOrInsert($lookup, [
            'value' => $value
        ]);
    }
}
