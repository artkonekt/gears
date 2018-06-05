<?php
/**
 * Contains the TreeBuilder class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-04-27
 *
 */

namespace Konekt\Gears\UI;

use Konekt\Gears\Registry\PreferencesRegistry;
use Konekt\Gears\Registry\SettingsRegistry;
use Konekt\Gears\Repository\PreferenceRepository;
use Konekt\Gears\Repository\SettingRepository;

class TreeBuilder
{
    /** @var Tree */
    protected $tree;

    /** @var SettingRepository */
    protected $settingRepository;

    /** @var PreferenceRepository */
    protected $preferenceRepository;

    /** @var array|null */
    protected $settings;

    /** @var SettingsRegistry */
    protected $settingsRegistry;

    /** @var PreferencesRegistry */
    protected $preferencesRegistry;

    /** @var bool */
    protected $lazyLoad;

    public function __construct(
        SettingRepository $settingRepository,
        PreferenceRepository $preferenceRepository,
        bool $lazyLoad = true
    ) {
        $this->tree = new Tree();

        $this->settingRepository    = $settingRepository;
        $this->preferenceRepository = $preferenceRepository;
        $this->settingsRegistry     = $settingRepository->getRegistry();
        $this->preferencesRegistry  = $preferenceRepository->getRegistry();
        $this->lazyLoad             = $lazyLoad;
    }

    public function getTree(): Tree
    {
        if ($this->lazyLoad) {
            $this->loadSettingValues();
        }
        return $this->tree;
    }

    /**
     * @return static
     */
    public function addRootNode(string $id, string $label = null)
    {
        $this->tree->createNode($id, $label);

        return $this;
    }

    /**
     * @return static
     */
    public function addChildNode(string $parentNodeId, $id, $label = null)
    {
        if ($parentNode = $this->tree->findNode($parentNodeId, true)) {
            $parentNode->createChild($id, $label);
        }

        return $this;
    }

    /**
     * @return static
     */
    public function addSettingItem(string $parentNodeId, $widget, $settingKey)
    {
        $node = $this->tree->findNode($parentNodeId, true);
        if ($node && $setting = $this->findSettingByKey($settingKey)) {
            $node->createSettingItem($widget, $setting['object'], $setting['value']);
        }

        return $this;
    }

    private function loadSettingValues()
    {
        if (!$this->settings) {
            $this->settings = $this->settingRepository->all();
        }

        foreach ($this->tree->nodes() as $node) {
            /** @var Node $node */
            foreach ($node->items() as $item) {
                /** @var SettingItem $item */
                $setting = $item->getSetting();
                $item->setValue($this->settings[$setting->key()] ?? $setting->default());
            }
        }
    }

    /**
     * @param string $key
     *
     * @return array|null
     */
    private function findSettingByKey(string $key)
    {
        if (!$this->settings && !$this->lazyLoad) {
            $this->settings = $this->settingRepository->all();
        }

        if ($setting = $this->settingsRegistry->get($key)) {
            return [
                'object' => $setting,
                'value'  => $this->settings[$key] ?? $setting->default()
            ];
        }
    }
}
