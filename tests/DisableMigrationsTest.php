<?php
/**
 * Contains the DisableMigrationsTest class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-06-20
 *
 */

namespace Konekt\Gears\Tests;

use Illuminate\Support\Facades\Schema;

class DisableMigrationsTest extends TestCase
{
    /** @test */
    public function migrations_are_not_loaded_if_disabled_in_configuration()
    {
        $this->assertFalse(Schema::hasTable('settings'));
        $this->assertFalse(Schema::hasTable('preferences'));
    }

    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        $app['config']->set('gears.migrations', false);
    }
}
