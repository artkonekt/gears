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

        $this->assertEquals($node, $tree->getNode('0427'));
    }
}
