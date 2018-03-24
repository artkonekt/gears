<?php
/**
 * Contains the BaseRegistry class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-03-18
 *
 */

namespace Konekt\Gears\Registry;

use Konekt\Gears\Contracts\Cog;

abstract class BaseRegistry
{
    /** @var \Illuminate\Support\Collection */
    protected $items;

    public function __construct()
    {
        $this->items = collect();
    }

    /**
     * Returns all the registered items
     *
     * @return array
     */
    public function all()
    {
        return $this->items->all();
    }

    /**
     * Returns all the defaults of the registered items
     */
    public function allDefaults()
    {
        return $this->items->map(function (Cog $item) {
            return $item->default();
        })->all();
    }

    /**
     * Returns whether an item is registered
     *
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key)
    {
        return $this->items->has($key);
    }

    /**
     * Returns an item from the registry by its key
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->items->get($key);
    }

    /**
     * Removes an item from the registry
     *
     * @param string $key
     */
    public function removeByKey(string $key)
    {
        $this->items->forget($key);
    }

    /**
     * Adds a new item by only passing its key
     *
     * @param string $key
     * @return mixed
     */
    abstract public function addByKey(string $key);
}
