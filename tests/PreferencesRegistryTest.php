<?php
/**
 * Contains the PreferencesRegistryTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-03-18
 *
 */

namespace Konekt\Gears\Tests;

use Konekt\Gears\Contracts\Preference;
use Konekt\Gears\Defaults\SimplePreference;
use Konekt\Gears\Registry\PreferencesRegistry;

class PreferencesRegistryTest extends \PHPUnit\Framework\TestCase
{
    /** @var PreferencesRegistry */
    private $registry;

    /**
     * @test
     */
    public function preferences_can_be_registered_with_an_object()
    {
        $preference = new SimplePreference('preference_key');

        $this->registry->add($preference);
        $this->assertTrue($this->registry->has('preference_key'));
        $this->assertCount(1, $this->registry->all());
    }

    /**
     * @test
     */
    public function preferences_can_be_registered_with_key()
    {
        $preference = $this->registry->addByKey('goodbye.stale');

        $this->assertInstanceOf(Preference::class, $preference);
        $this->assertTrue($this->registry->has('goodbye.stale'));
        $this->assertCount(1, $this->registry->all());
    }

    /**
     * @test
     */
    public function preferences_can_be_removed_by_object()
    {
        $preference = $this->registry->addByKey('smooth.ie');

        $this->assertInstanceOf(Preference::class, $preference);
        $this->assertTrue($this->registry->has('smooth.ie'));
        $this->assertCount(1, $this->registry->all());

        $this->registry->remove($preference);
        $this->assertFalse($this->registry->has('smooth.ie'));
        $this->assertCount(0, $this->registry->all());
    }

    /**
     * @test
     */
    public function preferences_can_be_removed_by_key()
    {
        $preference = new SimplePreference('it.can.be.anything');
        $this->registry->add($preference);

        $this->assertInstanceOf(Preference::class, $preference);
        $this->assertTrue($this->registry->has('it.can.be.anything'));
        $this->assertCount(1, $this->registry->all());

        $this->registry->removeByKey('it.can.be.anything');
        $this->assertFalse($this->registry->has('it.can.be.anything'));
        $this->assertCount(0, $this->registry->all());
    }

    /**
     * @test
     */
    public function preference_registered_with_objects_can_be_returned_by_key()
    {
        $addedPreference = new SimplePreference('swimming_pool');
        $this->registry->add($addedPreference);

        $returnedPreference = $this->registry->get('swimming_pool');

        $this->assertInstanceOf(Preference::class, $returnedPreference);
        $this->assertEquals($addedPreference->key(), $returnedPreference->key());
    }

    /**
     * @test
     */
    public function preference_registered_with_keys_can_be_returned_by_key()
    {
        $this->registry->addByKey('landsberger');

        $returnedPreference = $this->registry->get('landsberger');

        $this->assertInstanceOf(Preference::class, $returnedPreference);
        $this->assertEquals('landsberger', $returnedPreference->key());
    }

    protected function setUp()
    {
        parent::setUp();

        $this->registry = new PreferencesRegistry();
    }
}
