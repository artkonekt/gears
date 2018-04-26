<?php
/**
 * Contains the Node class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-04-26
 *
 */

namespace Konekt\Gears\UI;

use Illuminate\Support\Collection;

class Node
{
    /** @var string */
    private $id;

    /** @var string|null */
    private $label;

    /** @var Node|null */
    private $parent;

    /** @var Collection */
    private $children;

    /** @var Collection */
    private $items;

    public function __construct(string $id, $label = null)
    {
        $this->id       = $id;
        $this->label    = $label;
        $this->children = collect();
        $this->items    = collect();
    }

    /**
     * Returns the id of the node
     *
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    public function label(): string
    {
        return $this->label ?: $this->id;
    }

    public function setParent(Node $parent)
    {
        $this->parent = $parent;

        if (!$parent->hasChild($this->id())) {
            $parent->addChild($this);
        }
    }

    /**
     * @return Node|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    public function addChild(Node $child)
    {
        $this->children->put($child->id(), $child);

        if (is_null($child->getParent()) || $child->getParent()->id() != $this->id()) {
            $child->setParent($this);
        }
    }

    public function removeChild(Node $child)
    {
        $this->children->forget($child->id());
    }

    /**
     * @param string $id
     *
     * @return Node|null
     */
    public function getChild(string $id)
    {
        return $this->children->get($id);
    }

    public function hasChild(string $id): bool
    {
        return $this->children->has($id);
    }

    public function children(): array
    {
        return $this->children->all();
    }

    public function addItem(Item $item)
    {
        $this->items->push($item);
    }

    public function removeItem(Item $item)
    {
        $this->items = $this->items->reject(function(Item $value, $key) use ($item) {
            return $value->getCog()->key() == $item->getCog()->key();
        });
    }

    public function items(): array
    {
        return $this->items->all();
    }
}
