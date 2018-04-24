<?php
/**
 * Contains the Item class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-04-24
 *
 */

namespace Konekt\Gears\UI;

use Konekt\Gears\Contracts\Cog;
use Konekt\Gears\Contracts\Preference;
use Konekt\Gears\Contracts\Setting;

class Item
{
    /** @var Widget */
    private $widget;
    /**
     * @var Cog
     */
    private $cog;

    /**
     * Item constructor.
     *
     * @param string|array|Widget $widget
     * @param Cog                 $cog
     */
    public function __construct($widget, Cog $cog)
    {
        $this->widget = $widget instanceof Widget ? $widget : $this->createWidget($widget);
        $this->cog    = $cog;
    }

    /**
     * @return Widget
     */
    public function getWidget(): Widget
    {
        return $this->widget;
    }

    public function getCog(): Cog
    {
        return $this->cog;
    }

    /**
     * @return Setting|null
     */
    public function getSetting()
    {
        return $this->getCog() instanceof Setting ? $this->getCog() : null;
    }

    /**
     * @return Preference|null
     */
    public function getPreference()
    {
        return $this->getCog() instanceof Preference ? $this->getCog() : null;
    }

    /**
     * @param string|array $widget
     *
     * @return Widget
     */
    private function createWidget($widget): Widget
    {
        if (is_string($widget)) {
            return new Widget($widget);
        } elseif (is_array($widget)) {
            return new Widget($widget[0], $widget[1]);
        }

        throw new \InvalidArgumentException('Could not create widget from the passed arguments');
    }
}
