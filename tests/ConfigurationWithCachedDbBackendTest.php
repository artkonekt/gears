<?php
/**
 * Contains the ConfigurationWithCachedDbBackendTest class.
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
use Konekt\Gears\Backend\Drivers\CachedDatabase;
use Konekt\Gears\Backend\Drivers\Database;
use Konekt\Gears\Facades\Settings;
use Konekt\Gears\Registry\SettingsRegistry;

class ConfigurationWithCachedDbBackendTest extends TestCase
{
    use ConfiguresBackend;

    const DRIVER = 'cached_database';

    /**
     * @test
     */
    public function the_backend_is_the_class_of_database_driver()
    {
        $this->assertInstanceOf(CachedDatabase::class, $this->app->get('gears.backend'));
    }

    /**
     * @test
     */
    public function settings_are_saved_to_the_database_but_not_to_the_cache()
    {
        /** @var SettingsRegistry $registry */
        $registry = $this->app->get('gears.settings_registry');
        $registry->addByKey('random_key');
        
        Settings::set('random_key', 'Rnd_value');
        $this->assertEquals('Rnd_value', Settings::get('random_key'));

        // Cool, but did it really hit the database?
        $record = DB::table(Database::SETTINGS_TABLE_NAME)
                    ->select('*')
                    ->where('id', 'random_key')
                    ->first();
        $this->assertEquals('Rnd_value', $record->value);

        // Ok ok, but it hit the cache, didn't it?
        $this->assertTrue(
            Cache::has(
                $this->getSettingCacheKey('random_key')
            )
        );
    }
}
