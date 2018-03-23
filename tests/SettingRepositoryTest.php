<?php
/**
 * Contains the SettingRepositoryTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-03-20
 *
 */

namespace Konekt\Gears\Tests;

use Konekt\Gears\Backend\Drivers\Database;
use Konekt\Gears\Defaults\SimpleSetting;
use Konekt\Gears\Exceptions\UnregisteredSettingException;
use Konekt\Gears\Registry\SettingsRegistry;
use Konekt\Gears\Repository\SettingRepository;
use Konekt\Gears\Tests\Mocks\SettingWithDefault;

class SettingRepositoryTest extends TestCase
{
    /** @var SettingRepository */
    private $repo;

    /** @var SettingsRegistry */
    private $registry;

    /**
     * @test
     */
    public function only_values_of_registered_settings_can_be_retrieved()
    {
        $this->expectException(UnregisteredSettingException::class);
        $this->repo->get('unknown.setting.key');
    }

    /**
     * @test
     */
    public function only_values_of_registered_settings_can_be_saved()
    {
        $this->expectException(UnregisteredSettingException::class);
        $this->repo->set('unregistered_key', 'blah');
    }

    /**
     * @test
     */
    public function only_values_of_registered_settings_can_be_deleted()
    {
        $this->expectException(UnregisteredSettingException::class);
        $this->repo->forget('chumba wamba');
    }

    /**
     * @test
     */
    public function values_of_registered_settings_can_be_saved_and_retrieved()
    {
        $this->registry->addByKey('simple.setting');

        $this->repo->set('simple.setting', 'Winsstrasse');
        $this->assertEquals('Winsstrasse', $this->repo->get('simple.setting'));

        $this->registry->addByKey('another');

        $this->repo->set('another', 'Kollwitz');
        $this->assertEquals('Kollwitz', $this->repo->get('another'));
    }

    /**
     * @test
     */
    public function get_method_returns_the_default_value_of_an_unset_setting()
    {
        $this->registry->add(new SimpleSetting('http.port', 80));

        $this->assertEquals(80, $this->repo->get('http.port'));
    }

    /**
     * @test
     */
    public function values_of_registered_settings_can_be_deleted()
    {
        $this->registry->addByKey('memory');
        $this->registry->addByKey('cpu');

        $this->repo->set('memory', '16GB');
        $this->assertEquals('16GB', $this->repo->get('memory'));
        $this->repo->set('cpu', 'i7');
        $this->assertEquals('i7', $this->repo->get('cpu'));

        $this->repo->forget('memory');
        $this->assertNull($this->repo->get('memory'));
        $this->assertEquals('i7', $this->repo->get('cpu'));
    }

    /**
     * @test
     */
    public function values_of_all_registered_settings_can_be_retrieved_at_once()
    {
        $this->registry->addByKey('first');
        $this->registry->addByKey('second');
        $this->registry->addByKey('third');

        $this->assertCount(3, $this->repo->all());
        $this->assertNull($this->repo->all()['first']);
        $this->assertNull($this->repo->all()['second']);
        $this->assertNull($this->repo->all()['third']);

        $this->repo->set('first', 1);
        $this->repo->set('second', 22);

        $this->assertCount(3, $this->repo->all());
        $this->assertEquals(22, $this->repo->all()['second']);

        $this->repo->set('second', 2);

        $this->assertCount(3, $this->repo->all());

        $this->repo->set('third', 3);

        $allSettings = $this->repo->all();
        $this->assertCount(3, $allSettings);
        $this->assertEquals(1, $allSettings['first']);
        $this->assertEquals(2, $allSettings['second']);
        $this->assertEquals(3, $allSettings['third']);
    }

    /**
     * @test
     */
    public function all_method_returns_the_defaults_for_settings_that_have_no_saved_values()
    {
        $this->registry->addByKey('simple');
        $this->registry->add(new SimpleSetting('has_default', 'def value'));

        $this->assertCount(2, $this->repo->all());
        $this->assertNull($this->repo->get('simple'));
        $this->assertEquals('def value', $this->repo->get('has_default'));
    }

    /**
     * @test
     */
    public function values_of_registered_settings_can_be_mass_updated()
    {
        $this->registry->addByKey('six');
        $this->registry->addByKey('seven');
        $this->registry->addByKey('eight');

        $this->repo->update([
            'six'   => 6,
            'seven' => 7,
            'eight' => 8
        ]);

        $allSettings = $this->repo->all();

        $this->assertCount(3, $allSettings);

        $this->assertEquals(6, $allSettings['six']);
        $this->assertEquals(7, $allSettings['seven']);
        $this->assertEquals(8, $allSettings['eight']);

        $this->repo->update([
            'seven' => 71,
            'eight' => 82
        ]);

        $this->assertEquals(6, $this->repo->get('six'));
        $this->assertEquals(71, $this->repo->get('seven'));
        $this->assertEquals(82, $this->repo->get('eight'));
    }

    /**
     * @test
     */
    public function values_of_registered_settings_can_be_mass_deleted()
    {
        $this->registry->addByKey('heads');
        $this->registry->addByKey('shoulders');
        $this->registry->addByKey('knees');
        $this->registry->addByKey('toes');

        $this->repo->update([
            'heads'     => 'H',
            'shoulders' => 'S',
            'knees'     => 'K',
            'toes'      => 'T'
        ]);

        $this->assertCount(4, $this->repo->all());

        $this->repo->delete(['knees', 'toes']);

        $settings = $this->repo->all();
        $this->assertCount(4, $settings);

        $this->assertNull($settings['knees']);
        $this->assertNull($settings['toes']);

        $this->assertNull($this->repo->get('knees'));
        $this->assertNull($this->repo->get('toes'));
    }

    public function setUp()
    {
        parent::setUp();

        $this->registry = new SettingsRegistry();
        $this->repo     = new SettingRepository(new Database(), $this->registry);
    }
}
