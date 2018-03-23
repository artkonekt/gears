<?php
/**
 * Contains the PreferenceRepositoryTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-03-20
 *
 */

namespace Konekt\Gears\Tests;

use Konekt\Gears\Backend\Drivers\Database;
use Konekt\Gears\Exceptions\UnregisteredPreferenceException;
use Konekt\Gears\Registry\PreferencesRegistry;
use Konekt\Gears\Repository\PreferenceRepository;
use Konekt\Gears\Tests\Mocks\User;

class PreferenceRepositoryTest extends TestCase
{
    /** @var PreferenceRepository */
    private $repo;

    /** @var PreferencesRegistry */
    private $registry;

    /**
     * @test
     */
    public function only_values_of_registered_preferences_can_be_retrieved()
    {
        $this->expectException(UnregisteredPreferenceException::class);
        $this->repo->get('unknown.pref.key', 1);
    }

    /**
     * @test
     */
    public function only_values_of_registered_preferences_can_be_saved()
    {
        $this->expectException(UnregisteredPreferenceException::class);
        $this->repo->set('unregistered_pref', 'blah', 2);
    }

    /**
     * @test
     */
    public function only_values_of_registered_preferences_can_be_deleted()
    {
        $this->expectException(UnregisteredPreferenceException::class);
        $this->repo->forget('fukateke', 2);
    }

    /**
     * @test
     */
    public function values_of_registered_preferences_can_be_saved_and_retrieved()
    {
        $this->registry->addByKey('simple.preference');

        $this->repo->set('simple.preference', 'Garrone', 23);
        $this->assertEquals('Garrone', $this->repo->get('simple.preference', 23));
        $this->assertNotEquals('Garrone', $this->repo->get('simple.preference', 24));

        $this->registry->addByKey('some other preference');

        $this->repo->set('some other preference', 'Mars', 23);
        $this->repo->set('some other preference', 'Twix', 24);
        $this->assertEquals('Mars', $this->repo->get('some other preference', 23));
        $this->assertEquals('Twix', $this->repo->get('some other preference', 24));
    }

    /**
     * @test
     */
    public function it_falls_back_to_currently_logged_in_user_when_no_user_is_set()
    {
        $this->registry->addByKey('color');

        $user = User::create(['email' => 'bill.gates@chumbawamba.co.uk']);
        $this->be($user);

        $this->repo->set('color', 'blue', $user->id);
        $this->repo->set('color', 'red', 123456789);

        $this->assertEquals('blue', $this->repo->get('color'));

        $this->repo->set('color', 'cyan');
        $this->assertEquals('cyan', $this->repo->get('color', $user->id));
    }

    /**
     * @test
     */
    public function values_of_registered_preferences_can_be_deleted()
    {
        $this->registry->addByKey('memory');
        $this->registry->addByKey('cpu');

        $this->repo->set('memory', '16GB', 1);
        $this->assertEquals('16GB', $this->repo->get('memory', 1));
        $this->repo->set('cpu', 'i7', 1);
        $this->assertEquals('i7', $this->repo->get('cpu', 1));

        $this->repo->forget('memory', 1);
        $this->assertNull($this->repo->get('memory', 1));
        $this->assertEquals('i7', $this->repo->get('cpu', 1));
    }

    /**
     * @test
     */
    public function values_of_all_registered_preferences_can_be_retrieved_at_once()
    {
        $uid = 27;

        $this->registry->addByKey('erste');
        $this->registry->addByKey('zweite');
        $this->registry->addByKey('dritte');

        $this->assertCount(3, $this->repo->all($uid));

        $this->repo->set('erste', 1, $uid);
        $this->repo->set('zweite', 22, $uid);

        $this->assertCount(3, $this->repo->all($uid));

        $this->repo->set('zweite', 2, $uid);

        $this->assertCount(3, $this->repo->all($uid));

        $this->repo->set('dritte', 3, $uid);

        $allSettings = $this->repo->all($uid);
        $this->assertCount(3, $allSettings);
        $this->assertEquals(1, $allSettings['erste']);
        $this->assertEquals(2, $allSettings['zweite']);
        $this->assertEquals(3, $allSettings['dritte']);
    }

    /**
     * @test
     */
    public function values_of_registered_preferences_can_be_mass_updated()
    {
        $user = new \stdClass();
        $user->id = 35;

        $this->registry->addByKey('sechs');
        $this->registry->addByKey('sieben');
        $this->registry->addByKey('acht');

        $this->repo->update([
            'sechs'   => 6,
            'sieben' => 7,
            'acht' => 8
        ], $user);

        $allSettings = $this->repo->all($user);

        $this->assertCount(3, $allSettings);

        $this->assertEquals(6, $allSettings['sechs']);
        $this->assertEquals(7, $allSettings['sieben']);
        $this->assertEquals(8, $allSettings['acht']);

        $this->repo->update([
            'sieben' => 71,
            'acht' => 82
        ], $user);

        $this->assertEquals(6, $this->repo->get('sechs', $user));
        $this->assertEquals(71, $this->repo->get('sieben', $user));
        $this->assertEquals(82, $this->repo->get('acht', $user));
    }

    /**
     * @test
     */
    public function values_of_registered_preferences_can_be_mass_deleted()
    {
        $uid = '21';

        $this->registry->addByKey('heads');
        $this->registry->addByKey('shoulders');
        $this->registry->addByKey('knees');
        $this->registry->addByKey('toes');

        $this->repo->update([
            'heads'     => 'H',
            'shoulders' => 'S',
            'knees'     => 'K',
            'toes'      => 'T'
        ], $uid);

        $this->assertCount(4, $this->repo->all($uid));

        $this->repo->delete(['knees', 'toes'], $uid);

        $settings = $this->repo->all($uid);
        $this->assertCount(4, $settings);

        $this->assertNull($settings['knees']);
        $this->assertNull($settings['toes']);

        $this->assertNull($this->repo->get('knees', $uid));
        $this->assertNull($this->repo->get('toes', $uid));
    }

    public function setUp()
    {
        parent::setUp();
        
        $this->registry = new PreferencesRegistry();
        $this->repo     = new PreferenceRepository(new Database(), $this->registry);
    }
}
