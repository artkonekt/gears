<?php
/**
 * Contains the ServiceProviderTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-03-17
 *
 */

namespace Konekt\Gears\Tests;

use Illuminate\Support\Facades\Schema;

class ServiceProviderTest extends TestCase
{
    /**
     * @test
     */
    public function migrations_are_run()
    {
        $this->assertTrue(Schema::hasTable('settings'));
        $this->assertTrue(Schema::hasTable('preferences'));
    }
}
