<?php
/**
 * Contains the TreeBuilder class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-04-27
 *
 */

namespace Konekt\Gears\UI;

class TreeBuilder
{
    /** @var Tree */
    protected $tree;

    public function __construct()
    {
        $this->tree = new Tree();
    }

    public function getTree(): Tree
    {
        return $this->tree;
    }

    public function addRootNode(string $id, string $label = null): Node
    {
        return $this->tree->createNode($id, $label);
    }

    public function addChildNode(string $parentNodeId, $id, $label = null): Node
    {
        // continue work here...
        $parentNode = $this->tree->findNode($parentNodeId);
    }
}
