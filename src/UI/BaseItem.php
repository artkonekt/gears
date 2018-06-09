<?php
/**
 * Contains the BaseItem class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-04-24
 *
 */

namespace Konekt\Gears\UI;

use Konekt\Gears\Contracts\Cog;
use Konekt\Gears\Enums\CogType;

abstract class BaseItem
{
    use Sortable;
    
    /** @var Widget */
    private $widget;

    /** @var Cog */
    private $cog;

    /** @var mixed */
    private $value;

    /** @var CogType */
    protected $type;

    /**
     * Item constructor.
     *
     * @param string|array|Widget $widget
     * @param Cog                 $cog
     * @param null                $value
     */
    public function __construct($widget, Cog $cog, $value = null)
    {
        $this->widget = $widget instanceof Widget ? $widget : $this->createWidget($widget);
        $this->cog    = $cog;
        $this->value  = is_null($value) ? $cog->default() : $value;
    }

    /**
     * @return Widget
     */
    public function getWidget(): Widget
    {
        return $this->widget;
    }

    /**
     * Returns the value of the setting or preference
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the value of the setting or preference for the UI
     * NOTE: This DOES NOT SAVE the value in the DB!
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Returns the id of the item (gets generated from the key)
     *
     * @return string
     */
    public function getId(): string
    {
        return snake_case(
            class_basename($this->getCog())
            . '_' .
            str_replace('.', '_', $this->getKey())
        );
    }

    /**
     * Returns the setting or preference key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->cog->key();
    }

    /**
     * Returns the default value of the setting or preference
     *
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->cog->default();
    }

    public function getType(): CogType
    {
        return $this->type;
    }

    protected function getCog(): Cog
    {
        return $this->cog;
    }

    /**
     * @param string|array $widget
     *
     * @return Widget
     */
    protected function createWidget($widget): Widget
    {
        if (is_string($widget)) {
            return new Widget($widget);
        } elseif (is_array($widget)) {
            return new Widget($widget[0], $widget[1]);
        }

        throw new \InvalidArgumentException('Could not create widget from the passed arguments');
    }
}
