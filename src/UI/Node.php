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
use Konekt\Gears\Contracts\Preference;
use Konekt\Gears\Contracts\Setting;

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

    public function createChild(string $id, string $label = null)
    {
        $child = new static($id, $label);

        $this->addChild($child);

        return $child;
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

    public function addItem(BaseItem $item)
    {
        $this->items->push($item);
    }

    public function createSettingItem($widget, Setting $setting, $value = null): SettingItem
    {
        $item = new SettingItem($widget, $setting, $value);

        $this->items->push($item);

        return $item;
    }

    public function createPreferenceItem($widget, Preference $preference, $value = null): PreferenceItem
    {
        $item = new PreferenceItem($widget, $preference, $value);

        $this->items->push($item);

        return $item;
    }

    public function removeItem(BaseItem $item)
    {
        $this->items = $this->items->reject(function (BaseItem $value, $key) use ($item) {
            return $value->getKey() == $item->getKey();
        });
    }

    public function items(): array
    {
        return $this->items->all();
    }
}
