<?php
/**
 * Contains the UINodeTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-04-26
 *
 */

namespace Konekt\Gears\Tests;

use Konekt\Gears\Defaults\SimplePreference;
use Konekt\Gears\Defaults\SimpleSetting;
use Konekt\Gears\UI\Node;
use Konekt\Gears\UI\PreferenceItem;
use Konekt\Gears\UI\SettingItem;

class UINodeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function it_can_be_instantiated()
    {
        $node = new Node(1);

        $this->assertInstanceOf(Node::class, $node);
    }

    /**
     * @test
     */
    public function it_always_has_an_id()
    {
        $node = new Node('general_settings_tab');

        $this->assertEquals('general_settings_tab', $node->id());
    }

    /**
     * @test
     */
    public function it_can_have_a_label()
    {
        $node = new Node('id', 'Hello World');

        $this->assertEquals('Hello World', $node->label());
    }

    /**
     * @test
     */
    public function label_is_optional_and_falls_back_to_the_id_if_not_set()
    {
        $node = new Node('i am the id');

        $this->assertEquals($node->id(), $node->label());
    }

    /**
     * @test
     */
    public function it_can_have_a_parent_node()
    {
        $parent = new Node('general_settings_tab');
        $child  = new Node('app_settings_group');
        $child->setParent($parent);

        $this->assertEquals($parent, $child->getParent());
    }

    /**
     * @test
     */
    public function child_nodes_can_be_added_to_it()
    {
        $child1 = new Node('child1');
        $child2 = new Node('child2');

        $parent = new Node('parent');

        $parent->addChild($child1);
        $parent->addChild($child2);

        $this->assertCount(2, $parent->children());
    }

    /**
     * @test
     */
    public function child_node_can_be_created_with_it()
    {
        $node  = new Node('parent');
        $child = $node->createChild('child_id', 'Child Label');

        $this->assertInstanceOf(Node::class, $child);
        $this->assertCount(1, $node->children());
        $this->assertContains($child, $node->children());
        $this->assertEquals($node, $child->getParent());
    }

    /**
     * @test
     */
    public function child_nodes_can_be_returned_by_their_ids()
    {
        $child1 = new Node('child1');
        $child2 = new Node('child2');

        $parent = new Node('parent');

        $parent->addChild($child1);
        $parent->addChild($child2);

        $children = $parent->children();
        $this->assertEquals($child1, $children['child1']);
        $this->assertEquals($child2, $children['child2']);
        $this->assertEquals($child1, $parent->getChild('child1'));
        $this->assertEquals($child2, $parent->getChild('child2'));
    }

    /**
     * @test
     */
    public function child_nodes_can_be_removed()
    {
        $childA = new Node('child_a');
        $childB = new Node('child_b');

        $parent = new Node('parent');

        $parent->addChild($childA);
        $parent->addChild($childB);

        $this->assertCount(2, $parent->children());

        $parent->removeChild($childA);

        $this->assertCount(1, $parent->children());
        $this->assertFalse($parent->hasChild('child_a'));
    }

    /**
     * @test
     */
    public function when_setting_the_parent_the_node_will_be_among_parents_children()
    {
        $child  = new Node('child');
        $parent = new Node('parent');

        $child->setParent($parent);

        $this->assertTrue($parent->hasChild($child->id()));
    }

    /**
     * @test
     */
    public function when_adding_a_child_the_childrens_parent_will_be_this_node()
    {
        $nino  = new Node('nino');
        $padre = new Node('padre');

        $padre->addChild($nino);

        $this->assertEquals($padre, $nino->getParent());
    }

    /**
     * @test
     */
    public function it_has_items()
    {
        $node = new Node('hello');

        $settingItem = new SettingItem('text', new SimpleSetting('setting'), 'Hello, mi?');
        $node->addItem($settingItem);
        $this->assertCount(1, $node->items());

        $preferenceItem = new PreferenceItem('text', new SimplePreference('preferred_city'), 'Czikago');
        $node->addItem($preferenceItem);
        $this->assertCount(2, $node->items());
    }

    /**
     * @test
     */
    public function setting_items_can_be_created_with_it()
    {
        $node = new Node('vader', 'Darth Vader');

        $item = $node->createSettingItem('text', new SimpleSetting('setting.key'));

        $this->assertInstanceOf(SettingItem::class, $item);
        $this->assertContains($item, $node->items());
    }

    /**
     * @test
     */
    public function preference_items_can_be_created_with_it()
    {
        $node = new Node('mader', 'Light Mader');

        $item = $node->createPreferenceItem('text', new SimplePreference('pref.key'));

        $this->assertInstanceOf(PreferenceItem::class, $item);
        $this->assertContains($item, $node->items());
    }

    /**
     * @test
     */
    public function items_can_be_returned_as_array()
    {
        $node  = new Node('Group 1');
        $item1 = new SettingItem('text', new SimpleSetting('setting 1'));
        $item2 = new SettingItem('text', new SimpleSetting('setting 2'));

        $node->addItem($item1);
        $node->addItem($item2);

        $this->assertCount(2, $node->items());
        if (method_exists($this, 'assertIsArray')) {
            $this->assertIsArray($node->items());
        } else {
            $this->assertInternalType('array', $node->items());
        }
    }

    /**
     * @test
     */
    public function items_with_settings_can_be_removed()
    {
        $node         = new Node('Settings');
        $settingItem1 = new SettingItem('checkbox', new SimpleSetting('setting1'));
        $settingItem2 = new SettingItem('text', new SimpleSetting('setting2'));

        $node->addItem($settingItem1);
        $node->addItem($settingItem2);

        $this->assertCount(2, $node->items());

        $node->removeItem($settingItem1);

        $this->assertCount(1, $node->items());
        $this->assertNotContains($settingItem1, $node->items());
        $this->assertContains($settingItem2, $node->items());
    }

    /**
     * @test
     */
    public function items_with_preferences_can_be_removed()
    {
        $node      = new Node('Preferences');
        $prefItem1 = new PreferenceItem('checkbox', new SimplePreference('pref1'));
        $pretItem2 = new PreferenceItem('text', new SimplePreference('pref2'));

        $node->addItem($prefItem1);
        $node->addItem($pretItem2);

        $this->assertCount(2, $node->items());

        $node->removeItem($prefItem1);

        $this->assertCount(1, $node->items());
        $this->assertNotContains($prefItem1, $node->items());
        $this->assertContains($pretItem2, $node->items());
    }

    /**
     * @test
     */
    public function nodes_can_be_sorted()
    {
        $root = new Node('root');

        $nodeA = $root->createChild('A');
        $nodeB = $root->createChild('B');
        $nodeC = $root->createChild('C');

        $this->assertEquals(['A', 'B', 'C'], array_keys($root->children()));

        $nodeA->order = 150;
        $nodeB->order = 100;
        $nodeC->order = 50;

        $this->assertEquals(['C', 'B', 'A'], array_keys($root->children()));
    }

    /**
     * @test
     */
    public function items_can_be_sorted()
    {
        $node = new Node('humanism');

        $america = $node->createSettingItem('text', new SimpleSetting('miss_america'));
        $bikini  = new PreferenceItem('checkbox', new SimplePreference('has_bikini'));
        $node->addItem($bikini);

        $this->assertEquals(
            ['miss_america', 'has_bikini'],
            collect($node->items())->map(function ($item) {
                return $item->getKey();
            })->all()
        );

        $bikini->order  = 1;
        $america->order = 2;

        $this->assertEquals(
            ['has_bikini', 'miss_america'],
            collect($node->items())->map(function ($item) {
                return $item->getKey();
            })->all()
        );
    }
}
