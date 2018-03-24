<?php
/**
 * Contains the ConfigurationWithDbBackendTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-03-24
 *
 */

namespace Konekt\Gears\Tests;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Konekt\Gears\Backend\Drivers\Database;
use Konekt\Gears\Facades\Settings;
use Konekt\Gears\Registry\SettingsRegistry;

class ConfigurationWithDbBackendTest extends TestCase
{
    use ConfiguresBackend;

    const DRIVER = 'database';

    /**
     * @test
     */
    public function the_backend_is_the_class_of_database_driver()
    {
        $this->assertInstanceOf(Database::class, $this->app->get('gears.backend'));
    }

    /**
     * @test
     */
    public function settings_are_saved_to_the_database_but_not_to_the_cache()
    {
        /** @var SettingsRegistry $registry */
        $registry = $this->app->get('gears.settings_registry');
        $registry->addByKey('testing.is.like.icecream');

        Settings::set('testing.is.like.icecream', 'What??');
        $this->assertEquals('What??', Settings::get('testing.is.like.icecream'));

        // Cool, but did it really hit the database?
        $record = DB::table(Database::SETTINGS_TABLE_NAME)
                    ->select('*')
                    ->where('id', 'testing.is.like.icecream')
                    ->first();
        $this->assertEquals('What??', $record->value);

        // Ok ok, but it did not hit the cache, did it?
        $this->assertFalse(
            Cache::has(
                $this->getSettingCacheKey('testing.is.like.icecream')
            )
        );
    }


}
