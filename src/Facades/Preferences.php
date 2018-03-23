<?php
/**
 * Contains the Preferences facade accessor class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-03-23
 *
 */

namespace Konekt\Gears\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed get(string $key, $user = null)
 * @method static void set(string $key, mixed $value, $user = null)
 * @method static void forget(string $key, $user = null)
 * @method static array all($user = null)
 * @method static void update(array $settings, $user = null)
 * @method static void delete(array $keys, $user = null)
 */
class Preferences extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'gears.preferences';
    }
}
