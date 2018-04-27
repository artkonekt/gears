<?php
/**
 * Contains the Tree class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-04-27
 *
 */

namespace Konekt\Gears\UI;

class Tree
{
    /** @var \Illuminate\Support\Collection */
    protected $nodes;

    public function __construct()
    {
        $this->nodes = collect();
    }

    public function addNode(Node $node)
    {
        $this->nodes->put($node->id(), $node);
    }

    public function createNode(string $id, string $label = null): Node
    {
        $node = new Node($id, $label);
        $this->addNode($node);

        return $node;
    }

    /**
     * @param string $id
     *
     * @return Node|null
     */
    public function getNode(string $id)
    {
        return $this->nodes->get($id);
    }

    public function nodes(): array
    {
        return $this->nodes->all();
    }
}
