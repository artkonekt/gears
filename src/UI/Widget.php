<?php
/**
 * Contains the Widget class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-04-09
 *
 */

namespace Konekt\Gears\UI;

class Widget
{
    /** @var string */
    private $component;

    /** @var array */
    private $attributes;

    public function __construct(string $component, array $attributes = [])
    {
        $this->component  = $component;
        $this->attributes = $attributes;
    }

    /**
     * Returns the name of the (blade) component of the widget
     *
     * @return string
     */
    public function component()
    {
        return $this->component;
    }

    /**
     * Returns the array of attributes for the widget
     *
     * @return array
     */
    public function attributes()
    {
        return $this->attributes;
    }

    /**
     * Returns the value of an attribute, if it exists, null if not
     *
     * @param string $attribute
     *
     * @return mixed|null
     */
    public function getAttribute(string $attribute)
    {
        return $this->attributes[$attribute] ?? null;
    }

    /**
     * Set the value of an attribute
     *
     * @param string $attribute
     * @param mixed  $value
     */
    public function setAttribute(string $attribute, $value)
    {
        $this->attributes[$attribute] = $value;
    }
}
