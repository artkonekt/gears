<?php
/**
 * Contains the CacheDatabaseBackendTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-03-24
 *
 */

namespace Konekt\Gears\Tests;

use Illuminate\Contracts\Cache\Repository;
use Konekt\Gears\Backend\Drivers\CachedDatabase;
use Konekt\Gears\Backend\Drivers\Database;
use Konekt\Gears\Contracts\Backend;

class CacheDatabaseBackendTest extends TestCase
{
    /** @var CachedDatabase */
    private $backend;

    /** @var Database */
    private $db;

    public function setUp(): void
    {
        parent::setUp();

        $this->db      = new Database();
        $this->backend = new CachedDatabase(
            $this->app->make(Repository::class),
            $this->db
        );
    }

    /**
     * @test
     */
    public function app_can_instantiate_it()
    {
        $backend = $this->app->make(CachedDatabase::class);
        $this->assertInstanceOf(Backend::class, $backend);
        $this->assertInstanceOf(CachedDatabase::class, $backend);
    }

    /**
     * @test
     */
    public function it_can_return_all_the_values_from_the_database()
    {
        $this->db->setSetting('chipmunk', 17);
        $this->db->setSetting('squirrel', 0);

        $this->assertCount(2, $this->backend->allSettings());
        $this->assertEquals(17, $this->backend->allSettings()['chipmunk']);
        $this->assertEquals(0, $this->backend->allSettings()['squirrel']);
    }

    /**
     * @test
     */
    public function it_reads_all_values_from_the_db_only_once_after_cache_was_warmed()
    {
        $this->db->setSetting('little', 'gray');

        $this->assertCount(1, $this->backend->allSettings());
        $this->assertEquals('gray', $this->backend->allSettings()['little']);

        // Writing directly to the db, bypassing the cache
        $this->db->setSetting('medium', 'black');

        // Cache should not read the db after it has the value fetched
        $this->assertCount(1, $this->backend->allSettings());
        $this->assertArrayNotHasKey('medium', $this->backend->allSettings());
    }

    /**
     * @test
     */
    public function it_reads_single_values_from_the_db_only_once_after_cache_was_warmed()
    {
        $this->db->setSetting('android', 'HTC');

        $this->assertEquals('HTC', $this->backend->getSetting('android'));

        // Writing directly to the db, bypassing the cache
        $this->db->setSetting('Meizu', 'android');

        // Cache value should be used
        $this->assertEquals('HTC', $this->backend->getSetting('android'));
    }

    /**
     * @test
     */
    public function saving_a_setting_properly_saves_data_and_purges_the_cache()
    {
        $this->backend->setSetting('Dolly Buster', 'Nora Dvořáková');
        $this->assertEquals('Nora Dvořáková', $this->backend->getSetting('Dolly Buster'));
        $this->assertEquals('Nora Dvořáková', $this->backend->allSettings()['Dolly Buster']);

        $this->backend->setSetting('Dolly Buster', 'Nora Baumberger');
        $this->assertEquals('Nora Baumberger', $this->db->getSetting('Dolly Buster'));
        $this->assertEquals('Nora Baumberger', $this->backend->getSetting('Dolly Buster'));
        $this->assertEquals('Nora Baumberger', $this->backend->allSettings()['Dolly Buster']);
    }

    /**
     * @test
     */
    public function multiple_settings_can_be_added_at_once_and_they_are_doing_fine_thanks()
    {
        $this->backend->setSettings([
           'Prender el Alma' => 'Puente Roto',
           'Nicola Cruz'     => 'Sanación'
        ]);

        $all = $this->backend->allSettings();

        $this->assertCount(2, $all);
        $this->assertEquals('Sanación', $all['Nicola Cruz']);
        $this->assertEquals('Puente Roto', $all['Prender el Alma']);
        $this->assertEquals('Sanación', $this->backend->getSetting('Nicola Cruz'));
        $this->assertEquals('Puente Roto', $this->backend->getSetting('Prender el Alma'));
        $this->assertEquals('Sanación', $this->db->getSetting('Nicola Cruz'));
        $this->assertEquals('Puente Roto', $this->db->getSetting('Prender el Alma'));

        $this->backend->setSettings(['Nicola Cruz' => 'La Mirada']);

        $all = $this->backend->allSettings();

        $this->assertCount(2, $all);
        $this->assertEquals('La Mirada', $all['Nicola Cruz']);
        $this->assertEquals('Puente Roto', $all['Prender el Alma']);
        $this->assertEquals('La Mirada', $this->backend->getSetting('Nicola Cruz'));
        $this->assertEquals('Puente Roto', $this->backend->getSetting('Prender el Alma'));
        $this->assertEquals('La Mirada', $this->db->getSetting('Nicola Cruz'));
        $this->assertEquals('Puente Roto', $this->db->getSetting('Prender el Alma'));
    }

    /**
     * @test
     */
    public function individual_settings_can_be_removed()
    {
        $this->backend->setSettings([
            'one' => 1,
            'two' => 2
        ]);

        $this->assertCount(2, $this->backend->allSettings());
        $this->assertEquals(1, $this->backend->allSettings()['one']);
        $this->assertEquals(2, $this->backend->allSettings()['two']);

        $this->backend->removeSetting('one');

        $this->assertCount(1, $this->backend->allSettings());
        $this->assertCount(1, $this->db->allSettings());
        $this->assertNull($this->backend->getSetting('one'));
        $this->assertNull($this->db->getSetting('one'));
        $this->assertEquals(2, $this->backend->allSettings()['two']);
        $this->assertEquals(2, $this->db->allSettings()['two']);
    }

    /**
     * @test
     */
    public function multiple_settings_can_be_removed()
    {
        $this->backend->setSettings([
            'symfony' => '4.0',
            'phalcon' => '3.2',
            'zend'    => '3.0'
        ]);

        $this->assertCount(3, $this->backend->allSettings());
        $this->assertEquals('4.0', $this->backend->allSettings()['symfony']);
        $this->assertEquals('3.2', $this->backend->allSettings()['phalcon']);
        $this->assertEquals('3.0', $this->backend->allSettings()['zend']);

        $this->backend->removeSettings(['zend', 'phalcon']);

        $this->assertCount(1, $this->backend->allSettings());
        $this->assertCount(1, $this->db->allSettings());
        $this->assertNull($this->backend->getSetting('zend'));
        $this->assertNull($this->db->getSetting('zend'));
        $this->assertNull($this->backend->getSetting('phalcon'));
        $this->assertNull($this->db->getSetting('phalcon'));
        $this->assertEquals('4.0', $this->backend->allSettings()['symfony']);
        $this->assertEquals('4.0', $this->db->allSettings()['symfony']);
    }

    /**
     * @test
     */
    public function it_can_read_all_preferences_at_once_using_db_and_caches_results()
    {
        $uid = 192;
        $this->db->setPreferences([
            'x' => 'X',
            'y' => 'Y',
            'z' => 'Z'
        ], $uid);

        $all = $this->backend->allPreferences($uid);
        $this->assertCount(3, $all);
        $this->assertEquals('X', $all->get('x'));
        $this->assertEquals('Y', $all->get('y'));
        $this->assertEquals('Z', $all->get('z'));

        // Writing directly to db, bypassing the cache:
        $this->db->setPreference('x', 'XXX', $uid);
        // Value should be the cached one
        $all = $this->backend->allPreferences($uid);
        $this->assertEquals('X', $all->get('x'));
    }

    /**
     * @test
     */
    public function it_reads_individual_preferences_from_db_and_caches_them()
    {
        $tarzan = 1904;
        $jane   = 1911;
        $this->db->setPreference('hometown', 'Freidorf', $tarzan);
        $this->db->setPreference('hometown', 'Boyle', $jane);

        $this->assertEquals('Freidorf', $this->backend->getPreference('hometown', $tarzan));
        $this->assertEquals('Boyle', $this->backend->getPreference('hometown', $jane));

        // Setting value in db directly, bypassing cache:
        $this->db->setPreference('hometown', 'Temesvár', $tarzan);
        // Cached value should be returned:
        $this->assertEquals('Freidorf', $this->backend->getPreference('hometown', $tarzan));
    }

    /**
     * @test
     */
    public function it_writes_individual_preferences_to_db_and_properly_purges_cache()
    {
        $uid = 978;
        $this->db->setPreference('cake', 'chocolate', $uid);
        $this->assertEquals('chocolate', $this->backend->getPreference('cake', $uid));

        $this->backend->setPreference('cake', 'strawberry', $uid);
        $this->assertEquals('strawberry', $this->db->getPreference('cake', $uid));
        $this->assertEquals('strawberry', $this->backend->getPreference('cake', $uid));

        $this->backend->setPreference('cake', 'cheescake', $uid);
        $this->assertEquals('cheescake', $this->backend->getPreference('cake', $uid));
        $this->assertEquals('cheescake', $this->db->getPreference('cake', $uid));
    }

    /**
     * @test
     */
    public function it_can_bulk_write_preferences_to_db_and_properly_purges_cache()
    {
        $uid = 2013;
        $this->db->setPreferences([
            'toy'  => 'kufli',
            'food' => 'cucumber',
            'car'  => 'black'
        ], $uid);

        $all = $this->backend->allPreferences($uid);
        $this->assertCount(3, $all);
        $this->assertEquals('kufli', $all->get('toy'));
        $this->assertEquals('kufli', $this->backend->getPreference('toy', $uid));
        $this->assertEquals('kufli', $this->db->getPreference('toy', $uid));
        $this->assertEquals('cucumber', $all->get('food'));
        $this->assertEquals('cucumber', $this->backend->getPreference('food', $uid));
        $this->assertEquals('cucumber', $this->db->getPreference('food', $uid));
        $this->assertEquals('black', $all->get('car'));
        $this->assertEquals('black', $this->backend->getPreference('car', $uid));
        $this->assertEquals('black', $this->db->getPreference('car', $uid));

        $this->backend->setPreferences([
            'toy' => 'Lightning McQueen',
            'car' => 'red'
        ], $uid);

        $all = $this->backend->allPreferences($uid);
        $this->assertCount(3, $all);

        $this->assertEquals('Lightning McQueen', $all->get('toy'));
        $this->assertEquals('Lightning McQueen', $this->db->getPreference('toy', $uid));
        $this->assertEquals('Lightning McQueen', $this->backend->getPreference('toy', $uid));
        $this->assertEquals('cucumber', $all->get('food'));
        $this->assertEquals('cucumber', $this->backend->getPreference('food', $uid));
        $this->assertEquals('cucumber', $this->db->getPreference('food', $uid));
        $this->assertEquals('red', $all->get('car'));
        $this->assertEquals('red', $this->backend->getPreference('car', $uid));
        $this->assertEquals('red', $this->db->getPreference('car', $uid));

        $this->backend->setPreferences([
            'cake' => 'strawberry',
            'food' => 'yogurt'
        ], $uid);

        $all = $this->backend->allPreferences($uid);
        $this->assertCount(4, $all);

        $this->assertEquals('strawberry', $all->get('cake'));
        $this->assertEquals('strawberry', $this->backend->getPreference('cake', $uid));
        $this->assertEquals('strawberry', $this->db->getPreference('cake', $uid));
        $this->assertEquals('Lightning McQueen', $all->get('toy'));
        $this->assertEquals('Lightning McQueen', $this->db->getPreference('toy', $uid));
        $this->assertEquals('Lightning McQueen', $this->backend->getPreference('toy', $uid));
        $this->assertEquals('yogurt', $all->get('food'));
        $this->assertEquals('yogurt', $this->db->getPreference('food', $uid));
        $this->assertEquals('yogurt', $this->backend->getPreference('food', $uid));
        $this->assertEquals('red', $all->get('car'));
        $this->assertEquals('red', $this->db->getPreference('car', $uid));
        $this->assertEquals('red', $this->backend->getPreference('car', $uid));
    }

    /**
     * @test
     */
    public function individual_preferences_can_be_removed()
    {
        $user1 = 11;
        $user2 = 22;

        $this->backend->setPreferences([
            'color' => 'blue',
            'font'  => 'Raleway'
        ], $user1);

        $this->backend->setPreferences([
            'color' => 'green',
            'font'  => 'Lato'
        ], $user2);

        $this->assertCount(2, $this->backend->allPreferences($user1));
        $this->assertCount(2, $this->backend->allPreferences($user2));

        $this->backend->removePreference('color', $user1);
        $allForUser1 =  $this->backend->allPreferences($user1);
        $this->assertCount(1, $allForUser1);
        $this->assertFalse($allForUser1->has('color'));
        $this->assertNull($this->backend->getPreference('color', $user1));
        $this->assertNull($this->db->getPreference('color', $user1));
        $this->assertEquals('Raleway', $this->backend->getPreference('font', $user1));
        $this->assertEquals('Raleway', $allForUser1->get('font'));

        $this->backend->removePreference('font', $user2);
        $allForUser2 =  $this->backend->allPreferences($user2);
        $this->assertCount(1, $allForUser2);
        $this->assertFalse($allForUser2->has('font'));
        $this->assertNull($this->backend->getPreference('font', $user2));
        $this->assertNull($this->db->getPreference('font', $user2));
        $this->assertEquals('green', $this->backend->getPreference('color', $user2));
        $this->assertEquals('green', $allForUser2->get('color'));
    }

    /**
     * @test
     */
    public function multiple_preferences_can_be_removed()
    {
        $schumacher = 1969;
        $this->backend->setPreferences([
            'mclaren'    => 'silver',
            'benetton'   => 'blue',
            'ferrari'    => 'red'
        ], $schumacher);

        $all = $this->backend->allPreferences($schumacher);
        $this->assertCount(3, $all);
        $this->assertEquals('silver', $all['mclaren']);
        $this->assertEquals('blue', $all['benetton']);
        $this->assertEquals('red', $all['ferrari']);

        $this->backend->removePreferences(['ferrari', 'benetton'], $schumacher);

        $all = $this->backend->allPreferences($schumacher);
        $this->assertCount(1, $all);
        $this->assertCount(1, $this->db->allPreferences($schumacher));
        $this->assertNull($this->backend->getPreference('ferrari', $schumacher));
        $this->assertNull($this->db->getPreference('ferrari', $schumacher));
        $this->assertNull($this->backend->getPreference('benetton', $schumacher));
        $this->assertNull($this->db->getPreference('benetton', $schumacher));
        $this->assertEquals('silver', $all['mclaren']);
        $this->assertEquals('silver', $this->db->allPreferences($schumacher)['mclaren']);
    }
}
