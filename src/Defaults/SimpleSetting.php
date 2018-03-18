<?php
/**
 * Contains the SimpleSetting class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-03-18
 *
 */

namespace Konekt\Gears\Defaults;

use Konekt\Gears\Contracts\Setting;
use Konekt\Gears\Traits\SimpleCog;

class SimpleSetting implements Setting
{
    use SimpleCog;

    /** @var bool */
    private $syncWithConfig;

    public function __construct(string $key, $default = null, $options = null, $syncWithConfig = false)
    {
        $this->key            = $key;
        $this->default        = $default;
        $this->options        = $options;
        $this->syncWithConfig = $syncWithConfig;
    }

    /**
     * @inheritDoc
     */
    public function syncWithConfig()
    {
        return $this->syncWithConfig;
    }
}
