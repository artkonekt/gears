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

    public function createNode(string $id, string $label = null, int $order = null): Node
    {
        $node = new Node($id, $label);

        if (null !== $order) {
            $node->order = $order;
        }

        $this->addNode($node);

        return $node;
    }

    /**
     * Searches a node in the tree and returns it if it was found
     *
     * @param string $id
     * @param bool   $searchChildren
     *
     * @return Node|null
     */
    public function findNode(string $id, $searchChildren = false)
    {
        return $this->findByIdAmongChildren($id, $this->nodes(), $searchChildren);
    }

    public function nodes(): array
    {
        return $this->nodes->sortBy('order')->all();
    }

    private function findByIdAmongChildren(string $id, array $children, $recursive)
    {
        foreach ($children as $child) {
            /** @var Node $child */
            if ($id == $child->id()) {
                return $child;
            } elseif ($recursive && $child->hasChildren()) {
                $node = $this->findByIdAmongChildren($id, $child->children(), $recursive);
                if ($node) {
                    return $node;
                }
            }
        }

        return null;
    }
}
