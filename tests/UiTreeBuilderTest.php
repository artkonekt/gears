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
use Konekt\Gears\Registry\PreferencesRegistry;
use Konekt\Gears\Registry\SettingsRegistry;
use Konekt\Gears\Repository\PreferenceRepository;
use Konekt\Gears\Repository\SettingRepository;
use Konekt\Gears\UI\Node;
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
     * @test
     */
    public function it_returns_a_tree()
    {
        $this->assertInstanceOf(Tree::class, $this->builder->getTree());
    }

    /**
     * @test
     */
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

    /**
     * @test
     */
    public function child_nodes_can_be_added()
    {
        $this->builder->addRootNode('parent', 'Tatal nostru');
        $this->builder->addChildNode('parent', 'child', 'Copilul lui');

        $tree = $this->builder->getTree();

        $this->assertInstanceOf(Node::class, $tree->findNode('child', true));
    }

    /**
     * @test
     */
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

        $this->assertInstanceOf(SettingItem::class, $tab1->findItemByKey('my_setting1'));
        $this->assertInstanceOf(SettingItem::class, $tab1->findItemByKey('my_setting2'));
    }

    /**
     * @inheritDoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->backend              = new Database();
        $this->settingsRegistry     = new SettingsRegistry();
        $this->settingRepository    = new SettingRepository($this->backend, $this->settingsRegistry);
        $this->preferencesRegistry  = new PreferencesRegistry();
        $this->preferenceRepository = new PreferenceRepository($this->backend, $this->preferencesRegistry);

        $this->builder = new TreeBuilder(
            $this->settingsRegistry,
            $this->settingRepository,
            $this->preferencesRegistry,
            $this->preferenceRepository
        );
    }
}
