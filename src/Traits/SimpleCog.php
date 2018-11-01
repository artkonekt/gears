<?php
/**
 * Contains the SimpleCog trait.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-03-18
 *
 */

namespace Konekt\Gears\Traits;

trait SimpleCog
{
    /** @var string */
    private $key;

    private $default;

    private $options;

    /**
     * @inheritDoc
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * @inheritDoc
     */
    public function default()
    {
        return $this->default;
    }

    /**
     * @inheritDoc
     */
    public function isAllowed()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function options()
    {
        if (is_callable($this->options)) {
            return call_user_func($this->options);
        }

        return $this->options;
    }
}
