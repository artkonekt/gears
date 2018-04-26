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
use Konekt\Gears\UI\Item;
use Konekt\Gears\UI\Node;

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
        $child = new Node('app_settings_group');
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
        $child = new Node('child');
        $parent = new Node('parent');

        $child->setParent($parent);

        $this->assertTrue($parent->hasChild($child->id()));
    }

    /**
     * @test
     */
    public function when_adding_a_child_the_childrens_parent_will_be_this_node()
    {
        $nino = new Node('nino');
        $padre = new Node('padre');

        $padre->addChild($nino);

        $this->assertEquals($padre, $nino->getParent());
    }

    /**
     * @test
     */
    public function it_can_have_items()
    {
        $node = new Node('hello');
        $item = new Item('text', new SimpleSetting('setting'), 'Hello, mi?');

        $node->addItem($item);

        $this->assertCount(1, $node->items());
    }

    /**
     * @test
     */
    public function items_can_be_returned_as_array()
    {
        $node = new Node('Group 1');
        $item1 = new Item('text', new SimpleSetting('setting 1'));
        $item2 = new Item('text', new SimpleSetting('setting 2'));

        $node->addItem($item1);
        $node->addItem($item2);

        $this->assertCount(2, $node->items());
        $this->assertInternalType('array', $node->items());
    }

    /**
     * @test
     */
    public function items_with_settings_can_be_removed()
    {
        $node = new Node('Settings');
        $settingItem1 = new Item('checkbox', new SimpleSetting('setting1'));
        $settingItem2 = new Item('text', new SimpleSetting('setting2'));

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
        $node = new Node('Preferences');
        $prefItem1 = new Item('checkbox', new SimplePreference('pref1'));
        $pretItem2 = new Item('text', new SimplePreference('pref2'));

        $node->addItem($prefItem1);
        $node->addItem($pretItem2);

        $this->assertCount(2, $node->items());

        $node->removeItem($prefItem1);

        $this->assertCount(1, $node->items());
        $this->assertNotContains($prefItem1, $node->items());
        $this->assertContains($pretItem2, $node->items());
    }
}
