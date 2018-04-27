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

use Konekt\Gears\UI\Node;
use Konekt\Gears\UI\Tree;
use Konekt\Gears\UI\TreeBuilder;

class UiTreeBuilderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function it_returns_a_tree()
    {
        $builder = new TreeBuilder();

        $this->assertInstanceOf(Tree::class, $builder->getTree());
    }

    /**
     * @test
     */
    public function root_level_nodes_can_be_added()
    {
        $builder = new TreeBuilder();

        $node = $builder->addRootNode('abc', 'Abc Label');
        $tree = $builder->getTree();

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
        $builder = new TreeBuilder();

        $builder->addRootNode('parent', 'Tatal nostru');
        $builder->addChildNode('parent', 'child', 'Copilul lui');

        $tree = $builder->getTree();

        $this->assertInstanceOf(Node::class, $tree->findNode('child'));
    }
}
