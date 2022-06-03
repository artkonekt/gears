<?php
/**
 * Contains the UITreeBuilderTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-04-27
 *
 */

namespace Konekt\Gears\Tests;

use Konekt\Gears\Backend\Drivers\Database;
use Konekt\Gears\Enums\CogType;
use Konekt\Gears\Registry\PreferencesRegistry;
use Konekt\Gears\Registry\SettingsRegistry;
use Konekt\Gears\Repository\PreferenceRepository;
use Konekt\Gears\Repository\SettingRepository;
use Konekt\Gears\Tests\Mocks\User;
use Konekt\Gears\UI\Node;
use Konekt\Gears\UI\PreferenceItem;
use Konekt\Gears\UI\SettingItem;
use Konekt\Gears\UI\Tree;
use Konekt\Gears\UI\TreeBuilder;

class UiTreeBuilderTest extends TestCase
{
    /** @var SettingsRegistry */
    private $settingsRegistry;

    /** @var SettingRepository */
    private $settingRepository;

    /** @var PreferencesRegistry */
    private $preferencesRegistry;

    /** @var PreferenceRepository */
    private $preferenceRepository;

    /** @var Database */
    private $backend;

    /** @var TreeBuilder */
    private $builder;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->backend              = new Database();
        $this->settingsRegistry     = new SettingsRegistry();
        $this->settingRepository    = new SettingRepository($this->backend, $this->settingsRegistry);
        $this->preferencesRegistry  = new PreferencesRegistry();
        $this->preferenceRepository = new PreferenceRepository($this->backend, $this->preferencesRegistry);

        $this->builder = new TreeBuilder($this->settingRepository, $this->preferenceRepository);
    }

    /** @test */
    public function it_returns_a_tree()
    {
        $this->assertInstanceOf(Tree::class, $this->builder->getTree());
    }

    /** @test */
    public function root_level_nodes_can_be_added()
    {
        $this->builder->addRootNode('abc', 'Abc Label');
        $tree = $this->builder->getTree();
        $node = $tree->findNode('abc');

        $this->assertEquals('abc', $node->id());
        $this->assertEquals('Abc Label', $node->label());
        $this->assertContains($node, $tree->nodes());
        $this->assertEquals($node, $tree->findNode('abc'));
    }

    /** @test */
    public function child_nodes_can_be_added()
    {
        $this->builder->addRootNode('parent', 'Tatal nostru');
        $this->builder->addChildNode('parent', 'child', 'Copilul lui');

        $tree = $this->builder->getTree();

        $this->assertInstanceOf(Node::class, $tree->findNode('child', true));
    }

    /** @test */
    public function setting_items_can_be_added()
    {
        $this->settingsRegistry->addByKey('my_setting1');
        $this->settingsRegistry->addByKey('my_setting2');
        $this->builder->addRootNode('tab1');
        $this->builder->addSettingItem('tab1', 'text', 'my_setting1');
        $this->builder->addSettingItem('tab1', 'text', 'my_setting2');

        $tree = $this->builder->getTree();
        $tab1 = $tree->findNode('tab1');

        $this->assertCount(2, $tab1->items());

        $item1 = $tab1->findItemByKey('my_setting1');
        $item2 = $tab1->findItemByKey('my_setting2');

        $this->assertInstanceOf(SettingItem::class, $item1);
        $this->assertInstanceOf(SettingItem::class, $item2);

        $this->assertTrue(CogType::SETTING()->equals($item1->getType()));
        $this->assertTrue(CogType::SETTING()->equals($item2->getType()));
    }

    /** @test */
    public function preference_items_can_be_added()
    {
        $this->preferencesRegistry->addByKey('my_pref1');
        $this->preferencesRegistry->addByKey('my_pref2');
        $this->builder->addRootNode('tab1');
        $this->builder->addPreferenceItem('tab1', 'text', 'my_pref1');
        $this->builder->addPreferenceItem('tab1', 'text', 'my_pref2');

        $tree = $this->builder->getTree();
        $tab1 = $tree->findNode('tab1');

        $this->assertCount(2, $tab1->items());

        $item1 = $tab1->findItemByKey('my_pref1');
        $item2 = $tab1->findItemByKey('my_pref2');

        $this->assertInstanceOf(PreferenceItem::class, $item1);
        $this->assertInstanceOf(PreferenceItem::class, $item2);
        $this->assertTrue(CogType::PREFERENCE()->equals($item1->getType()));
        $this->assertTrue(CogType::PREFERENCE()->equals($item2->getType()));
    }

    /** @test */
    public function it_loads_all_the_setting_values_in_any_depth()
    {
        $this->settingsRegistry->addByKey('setting10');
        $this->settingsRegistry->addByKey('setting11');
        $this->settingsRegistry->addByKey('setting20');
        $this->settingsRegistry->addByKey('setting21');
        $this->settingsRegistry->addByKey('setting22');
        $this->settingsRegistry->addByKey('setting23');
        $this->settingsRegistry->addByKey('setting30');

        $this->settingRepository->update([
            'setting10' => 'value10',
            'setting11' => 'value11',
            'setting20' => 'value20',
            'setting21' => 'value21',
            'setting22' => 'value22',
            'setting23' => 'value23',
            'setting30' => 'value30'
        ]);

        $this->builder->addRootNode('tab1');
        $this->builder->addSettingItem('tab1', 'text', 'setting10');
        $this->builder->addSettingItem('tab1', 'text', 'setting11');

        $this->builder->addChildNode('tab1', 'tab2');
        $this->builder->addSettingItem('tab2', 'text', 'setting20');
        $this->builder->addSettingItem('tab2', 'text', 'setting21');
        $this->builder->addSettingItem('tab2', 'text', 'setting22');
        $this->builder->addSettingItem('tab2', 'text', 'setting23');

        $this->builder->addChildNode('tab2', 'tab3');
        $this->builder->addSettingItem('tab3', 'text', 'setting30');

        $tree = $this->builder->getTree();

        $this->assertEquals('value10', $tree->findNode('tab1')->findItemByKey('setting10')->getValue());
        $this->assertEquals('value11', $tree->findNode('tab1', true)->findItemByKey('setting11')->getValue());
        $this->assertEquals('value20', $tree->findNode('tab2', true)->findItemByKey('setting20')->getValue());
        $this->assertEquals('value21', $tree->findNode('tab2', true)->findItemByKey('setting21')->getValue());
        $this->assertEquals('value22', $tree->findNode('tab2', true)->findItemByKey('setting22')->getValue());
        $this->assertEquals('value23', $tree->findNode('tab2', true)->findItemByKey('setting23')->getValue());
        $this->assertEquals('value30', $tree->findNode('tab3', true)->findItemByKey('setting30')->getValue());
    }

    /** @test */
    public function it_loads_all_the_preference_values_in_any_depth()
    {
        $user = User::create(['email' => 'duro.dora@marnemjobbik.hu']);
        $this->be($user);

        $this->preferencesRegistry->addByKey('prefA1');
        $this->preferencesRegistry->addByKey('prefB1');
        $this->preferencesRegistry->addByKey('prefB2');
        $this->preferencesRegistry->addByKey('prefC1');
        $this->preferencesRegistry->addByKey('prefD1');
        $this->preferencesRegistry->addByKey('prefD2');

        $this->preferenceRepository->update([
            'prefA1' => 'valueA1',
            'prefB1' => 'valueB1',
            'prefB2' => 'valueB2',
            'prefC1' => 'valueC1',
            'prefD1' => 'valueD1',
            'prefD2' => 'valueD2',
        ], $user);

        $this->builder->addRootNode('tabA');
        $this->builder->addPreferenceItem('tabA', 'text', 'prefA1');

        $this->builder->addChildNode('tabA', 'tabB');
        $this->builder->addPreferenceItem('tabB', 'text', 'prefB1');
        $this->builder->addPreferenceItem('tabB', 'text', 'prefB2');

        $this->builder->addChildNode('tabB', 'tabC');
        $this->builder->addPreferenceItem('tabC', 'text', 'prefC1');

        $this->builder->addChildNode('tabC', 'tabD');
        $this->builder->addPreferenceItem('tabD', 'text', 'prefD1');
        $this->builder->addPreferenceItem('tabD', 'text', 'prefD2');

        $tree = $this->builder->getTree();

        $this->assertEquals('valueA1', $tree->findNode('tabA', true)->findItemByKey('prefA1')->getValue());

        $this->assertEquals('valueB1', $tree->findNode('tabB', true)->findItemByKey('prefB1')->getValue());
        $this->assertEquals('valueB2', $tree->findNode('tabB', true)->findItemByKey('prefB2')->getValue());

        $this->assertEquals('valueC1', $tree->findNode('tabC', true)->findItemByKey('prefC1')->getValue());

        $this->assertEquals('valueD1', $tree->findNode('tabD', true)->findItemByKey('prefD1')->getValue());
        $this->assertEquals('valueD2', $tree->findNode('tabD', true)->findItemByKey('prefD2')->getValue());
    }

    /** @test */
    public function setting_and_preference_items_can_be_added_to_the_same_tree()
    {
        $user = User::create(['email' => 'borat@bruno.ali-g.kz']);
        $this->be($user);

        $this->settingsRegistry->addByKey('settingA1');
        $this->settingsRegistry->addByKey('settingB1');
        $this->settingsRegistry->addByKey('settingB2');
        $this->preferencesRegistry->addByKey('preferenceC1');
        $this->preferencesRegistry->addByKey('preferenceC2');
        $this->preferencesRegistry->addByKey('preferenceC3');

        $this->settingRepository->update([
            'settingA1' => 'valueA1',
            'settingB1' => 'valueB1',
            'settingB2' => 'valueB2'
        ]);

        $this->preferenceRepository->update([
            'preferenceC1' => 'valueC1',
            'preferenceC2' => 'valueC2',
            'preferenceC3' => 'valueC3',
        ], $user);

        $this->builder->addRootNode('tabA');
        $this->builder->addSettingItem('tabA', 'text', 'settingA1');

        $this->builder->addChildNode('tabA', 'tabB');
        $this->builder->addSettingItem('tabB', 'text', 'settingB1');
        $this->builder->addSettingItem('tabB', 'text', 'settingB2');

        $this->builder->addChildNode('tabA', 'tabC');
        $this->builder->addPreferenceItem('tabC', 'text', 'preferenceC1');
        $this->builder->addPreferenceItem('tabC', 'text', 'preferenceC2');
        $this->builder->addPreferenceItem('tabC', 'text', 'preferenceC3');

        $tree = $this->builder->getTree();

        $this->assertEquals('valueA1', $tree->findNode('tabA')->findItemByKey('settingA1')->getValue());

        $this->assertEquals('valueB1', $tree->findNode('tabB', true)->findItemByKey('settingB1')->getValue());
        $this->assertEquals('valueB2', $tree->findNode('tabB', true)->findItemByKey('settingB2')->getValue());

        $this->assertEquals('valueC1', $tree->findNode('tabC', true)->findItemByKey('preferenceC1')->getValue());
        $this->assertEquals('valueC2', $tree->findNode('tabC', true)->findItemByKey('preferenceC2')->getValue());
        $this->assertEquals('valueC3', $tree->findNode('tabC', true)->findItemByKey('preferenceC3')->getValue());
    }

    /** @test */
    public function order_of_nodes_can_be_specified()
    {
        $this->builder->addRootNode('abc', 'Abc Label', 20);
        $this->builder->addRootNode('def', 'Def Label', 10);
        $this->builder->addRootNode('ghi', 'Abc Label', 15);

        $this->builder->addChildNode('abc', 'AAA', null, 2);
        $this->builder->addChildNode('abc', 'BBB', null, 1);

        $tree = $this->builder->getTree();

        $this->assertEquals(['def', 'ghi', 'abc'], array_keys($tree->nodes()));
        $this->assertEquals(['BBB', 'AAA'], array_keys($tree->findNode('abc')->children()));
    }

    /** @test */
    public function order_of_items_can_be_specified()
    {
        $this->settingsRegistry->addByKey('habits');
        $this->settingsRegistry->addByKey('art');
        $this->settingsRegistry->addByKey('economy');

        $this->builder->addRootNode('culture');
        $this->builder->addSettingItem('culture', 'text', 'art', 20);
        $this->builder->addSettingItem('culture', 'text', 'economy', 200);
        $this->builder->addSettingItem('culture', 'text', 'habits', 2);

        $tree = $this->builder->getTree();

        $this->assertEquals(
            ['habits', 'art', 'economy'],
            collect($tree->findNode('culture')->items())->map(function ($item) {
                return $item->getKey();
            })->all()
        );
    }

    /** @test */
    public function it_fetches_the_right_preferences_when_using_as_singleton()
    {
        $userRed = User::create(['email' => 'robert@red.ford']);
        $userWhite = User::create(['email' => 'robert@de.niro']);
        $userGreen = User::create(['email' => 'robert@de.laney']);

        $this->preferencesRegistry->addByKey('color');
        $this->preferencesRegistry->addByKey('theme');

        $this->preferenceRepository->set('color', 'red', $userRed);
        $this->preferenceRepository->set('color', 'white', $userWhite);
        $this->preferenceRepository->set('color', 'green', $userGreen);

        $this->preferenceRepository->set('theme', 'Ford', $userRed);
        $this->preferenceRepository->set('theme', 'Niro', $userWhite);
        $this->preferenceRepository->set('theme', 'Laney', $userGreen);

        app()->singleton('gears.test.tree-builder', fn () => new TreeBuilder($this->settingRepository, $this->preferenceRepository));
        app('gears.test.tree-builder')->addRootNode('general', __('General Settings'), 100)
            ->addChildNode('general', 'defaults', 'Defaults')
            ->addPreferenceItem(
                'defaults',
                ['select', ['label' => 'Color']],
                'color'
            )
            ->addPreferenceItem(
                'defaults',
                ['select', ['label' => 'Theme']],
                'theme'
            );

        $this->assertInstanceOf(TreeBuilder::class, app()->get('gears.test.tree-builder'));

        $this->actingAs($userRed);
        /** @var Tree $tree */
        $tree = app('gears.test.tree-builder')->getTree();
        $this->assertEquals($tree->findNode('defaults', true)->findItemByKey('color')->getValue(), 'red');
        $this->assertEquals($tree->findNode('defaults', true)->findItemByKey('theme')->getValue(), 'Ford');

        $this->actingAs($userWhite);
        /** @var Tree $tree */
        $tree = app('gears.test.tree-builder')->getTree();
        $this->assertEquals($tree->findNode('defaults', true)->findItemByKey('color')->getValue(), 'white');
        $this->assertEquals($tree->findNode('defaults', true)->findItemByKey('theme')->getValue(), 'Niro');

        $this->actingAs($userGreen);
        /** @var Tree $tree */
        $tree = app('gears.test.tree-builder')->getTree();
        $this->assertEquals($tree->findNode('defaults', true)->findItemByKey('color')->getValue(), 'green');
        $this->assertEquals($tree->findNode('defaults', true)->findItemByKey('theme')->getValue(), 'Laney');
    }
}
