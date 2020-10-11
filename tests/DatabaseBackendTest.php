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

    public function setUp(): void
    {
        parent::setUp();

        $this->backend = new Database();
    }

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

    /**
     * @test
     */
    public function settings_can_be_deleted()
    {
        $this->backend->setSetting('shipping', 'DPD');
        $this->backend->setSetting('payment', 'Braintree');
        $this->backend->setSetting('notification', 'SMS');

        $settings = $this->backend->allSettings();
        $this->assertCount(3, $settings);

        $this->backend->removeSetting('shipping');

        $settings = $this->backend->allSettings();
        $this->assertCount(2, $settings);
        $this->assertFalse($settings->has('shipping'));

        $this->assertTrue($settings->has('payment'));
        $this->assertEquals('Braintree', $settings->get('payment'));

        $this->assertTrue($settings->has('notification'));
        $this->assertEquals('SMS', $settings->get('notification'));
    }

    /**
     * @test
     */
    public function preferences_can_be_deleted()
    {
        $this->backend->setPreference('color', 'green', 3);
        $this->backend->setPreference('font', 'Lato', 3);
        $this->backend->setPreference('weight', 300, 3);
        $this->backend->setPreference('size', 17, 3);

        $preferences = $this->backend->allPreferences(3);
        $this->assertCount(4, $preferences);

        $this->backend->removePreference('size', 3);

        $preferences = $this->backend->allPreferences(3);
        $this->assertCount(3, $preferences);
        $this->assertFalse($preferences->has('size'));

        $this->assertTrue($preferences->has('color'));
        $this->assertEquals('green', $preferences->get('color'));

        $this->assertTrue($preferences->has('font'));
        $this->assertEquals('Lato', $preferences->get('font'));

        $this->assertTrue($preferences->has('weight'));
        $this->assertEquals(300, $preferences->get('weight'));
    }

    /**
     * @test
     */
    public function settings_can_be_bulk_set()
    {
        $this->backend->setSettings([
            'bear'   => 'round',
            'rabbit' => 'long',
            'dog'    => 'dangling'
        ]);

        $this->assertEquals('round', $this->backend->getSetting('bear'));
        $this->assertEquals('long', $this->backend->getSetting('rabbit'));
        $this->assertEquals('dangling', $this->backend->getSetting('dog'));
    }

    /**
     * @test
     */
    public function preferences_can_be_bulk_set()
    {
        $justin = 9;
        $sarah  = 10;

        $this->backend->setPreferences([
            'food'  => 'hamburger',
            'drink' => 'coke',
            'drug'  => 'alcohol'
        ], $justin);

        $this->backend->setPreferences([
            'food'  => 'caviar',
            'drink' => 'champagne',
            'drug'  => 'sex'
        ], $sarah);

        $this->assertEquals('hamburger', $this->backend->getPreference('food', $justin));
        $this->assertEquals('coke', $this->backend->getPreference('drink', $justin));
        $this->assertEquals('alcohol', $this->backend->getPreference('drug', $justin));

        $sarahsPreferences = $this->backend->allPreferences($sarah);

        $this->assertCount(3, $sarahsPreferences);
        $this->assertEquals('caviar', $sarahsPreferences['food']);
        $this->assertEquals('champagne', $sarahsPreferences['drink']);
        $this->assertEquals('sex', $sarahsPreferences['drug']);
    }

    /**
     * @test
     */
    public function settings_can_be_bulk_deleted()
    {
        $this->backend->setSettings([
            'gold'   => 'yellow',
            'silver' => 'shiny',
            'stone'  => 'pale'
        ]);

        $this->assertCount(3, $this->backend->allSettings());

        $this->backend->removeSettings(['stone', 'silver']);

        $settings = $this->backend->allSettings();
        $this->assertCount(1, $settings);

        $this->assertTrue($settings->has('gold'));
        $this->assertFalse($settings->has('stone'));
        $this->assertFalse($settings->has('silver'));

        $this->assertNull($settings->get('silver'));
        $this->assertNull($settings->get('stone'));
        $this->assertEquals('yellow', $settings->get('gold'));
    }

    /**
     * @test
     */
    public function preferences_can_be_bulk_deleted()
    {
        $mia     = 19700429;
        $vincent = 19540218;

        $this->backend->setPreferences([
            'dance'    => 'twist',
            'trophy'   => true,
            'prefers'  => 'cocaine',
            'overdose' => true
        ], $mia);

        $this->backend->setPreferences([
            'dance'    => 'twist',
            'trophy'   => true,
            'prefers'  => 'heroine',
            'overdose' => false
        ], $vincent);

        $this->assertCount(4, $this->backend->allPreferences($mia));
        $this->assertCount(4, $this->backend->allPreferences($vincent));

        $this->backend->removePreferences(['prefers', 'overdose'], $mia);

        $miasPrefs = $this->backend->allPreferences($mia);
        $this->assertCount(2, $miasPrefs);
        $this->assertFalse($miasPrefs->has('prefers'));
        $this->assertNull($this->backend->getPreference('prefers', $mia));
        $this->assertFalse($miasPrefs->has('overdose'));
        $this->assertNull($this->backend->getPreference('overdose', $mia));

        $this->assertEquals(true, $this->backend->getPreference('trophy', $mia));
        $this->assertEquals('twist', $this->backend->getPreference('dance', $mia));

        $this->backend->removePreferences(['trophy', 'overdose'], $vincent);

        $vincentsPrefs = $this->backend->allPreferences($vincent);
        $this->assertCount(2, $vincentsPrefs);
        $this->assertFalse($vincentsPrefs->has('trophy'));
        $this->assertNull($this->backend->getPreference('trophy', $vincent));
        $this->assertFalse($vincentsPrefs->has('overdose'));
        $this->assertNull($this->backend->getPreference('overdose', $vincent));

        $this->assertEquals('heroine', $this->backend->getPreference('prefers', $vincent));
        $this->assertEquals('twist', $this->backend->getPreference('dance', $vincent));
    }
}
