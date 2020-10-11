<?php
/**
 * Contains the UITreeTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-04-27
 *
 */

namespace Konekt\Gears\Tests;

use Konekt\Gears\UI\Node;
use Konekt\Gears\UI\Tree;

class UITreeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function it_can_be_instantiated()
    {
        $tree = new Tree();

        $this->assertInstanceOf(Tree::class, $tree);
    }

    /**
     * @test
     */
    public function nodes_can_be_added_to_it()
    {
        $tree  = new Tree();
        $node1 = new Node('top_level_node1');
        $node2 = new Node('top_level_node2');

        $tree->addNode($node1);
        $tree->addNode($node2);

        $this->assertCount(2, $tree->nodes());
        $this->assertContains($node1, $tree->nodes());
        $this->assertContains($node2, $tree->nodes());
    }

    /**
     * @test
     */
    public function nodes_can_be_created_with_it()
    {
        $tree = new Tree();
        $node = $tree->createNode('node_id', 'Node Label');

        $this->assertInstanceOf(Node::class, $node);
        $this->assertInstanceOf(Node::class, $tree->nodes()['node_id']);
    }

    /**
     * @test
     */
    public function nodes_can_be_retrieved_by_id()
    {
        $tree = new Tree();

        $node = $tree->createNode('0427');
        $tree->createNode('0428');

        $this->assertEquals($node, $tree->findNode('0427'));
    }

    /**
     * @test
     */
    public function node_can_be_found_among_child_nodes_as_well()
    {
        $tree = new Tree();
        $top1 = $tree->createNode('top1');
        $top2 = $tree->createNode('top2');
        $top3 = $tree->createNode('top3');

        $top1->createChild('top1.child1');
        $top1->createChild('top1.child2');
        $top1->createChild('top1.child3');

        $top2->createChild('top2.child1');
        $top2->createChild('top2.child2');

        $top3->createChild('top3.child1');

        $this->assertEquals('top1', $tree->findNode('top1', false)->id());

        $this->assertNull($tree->findNode('top3.child1', false));
        $this->assertEquals('top3.child1', $tree->findNode('top3.child1', true)->id());
    }

    /**
     * @test
     */
    public function root_nodes_can_be_sorted()
    {
        $tree = new Tree();

        $tree->createNode('Toyota');
        $tree->createNode('Nissan');
        $tree->createNode('Kia');

        $this->assertEquals(['Toyota', 'Nissan', 'Kia'], array_keys($tree->nodes()));

        $tree->findNode('Toyota')->order = 101;
        $tree->findNode('Kia')->order    = 100;
        $tree->findNode('Nissan')->order = 99;

        $this->assertEquals(['Nissan', 'Kia', 'Toyota'], array_keys($tree->nodes()));
    }
}
