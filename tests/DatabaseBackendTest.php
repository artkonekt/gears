<?php
/**
 * Contains the DatabaseBackendTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-03-18
 *
 */

namespace Konekt\Gears\Tests;

use Konekt\Gears\Backend\Drivers\Database;
use Konekt\Gears\Contracts\Backend;

class DatabaseBackendTest extends TestCase
{
    /** @var Backend */
    private $backend;

    /**
     * @test
     */
    public function it_can_be_used_for_saving_and_retrieving_individual_setting_values()
    {
        $this->backend->setSetting('knip.rode', 1355);

        $this->assertEquals(1355, $this->backend->getSetting('knip.rode'));
    }

    /**
     * @test
     */
    public function it_can_be_used_for_saving_and_retrieving_individual_preference_values()
    {
        $this->backend->setPreference('greifs.walder', '156a', 1);

        $this->assertEquals('156a', $this->backend->getPreference('greifs.walder', 1));
    }

    /**
     * @test
     */
    public function each_user_has_separate_preferences()
    {
        $this->backend->setPreference('park', 'forest', 2);
        $this->backend->setPreference('park', 'field', 3);
        $this->backend->setPreference('bumm', 'bamm', 3);

        $this->assertEquals('forest', $this->backend->getPreference('park', 2));
        $this->assertEquals('field', $this->backend->getPreference('park', 3));
        $this->assertCount(1, $this->backend->allPreferences(2));
        $this->assertCount(2, $this->backend->allPreferences(3));
    }

    /**
     * @test
     */
    public function all_preferences_can_be_retrieved()
    {
        $this->backend->setPreference('sith', 'Ristin Oth', 4);
        $this->backend->setPreference('jedi', 'Tilafu Lop', 4);

        $preferences = $this->backend->allPreferences(4);

        $this->assertCount(2, $preferences);
        $this->assertEquals('Ristin Oth', $preferences->get('sith'));
        $this->assertEquals('Tilafu Lop', $preferences->get('jedi'));
    }

    /**
     * @test
     */
    public function all_settings_can_be_retrieved()
    {
        $this->backend->setSetting('make', 'BMW');
        $this->backend->setSetting('type', '528i');
        $this->backend->setSetting('code', 'E12');

        $settings = $this->backend->allSettings();

        $this->assertCount(3, $settings);
        $this->assertEquals('BMW', $settings->get('make'));
        $this->assertEquals('E12', $settings->get('code'));
        $this->assertEquals('528i', $settings->get('type'));
    }

    public function setUp()
    {
        parent::setUp();

        $this->backend = new Database();
    }


}
