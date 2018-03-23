<?php
/**
 * Contains the Settings facade accessor class.
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
 * @method static mixed get(string $key)
 * @method static void set(string $key, mixed $value)
 * @method static void forget(string $key)
 * @method static array all()
 * @method static void update(array $settings)
 * @method static void delete(array $keys)
 */
class Settings extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'gears.settings';
    }
}
