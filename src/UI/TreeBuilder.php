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

use Konekt\Gears\Enums\CogType;
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

    /** @var array|null */
    protected $preferences;

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
            $this->loadValues();
        }

        return $this->tree;
    }

    /**
     * @return static
     */
    public function addRootNode(string $id, string $label = null, int $order = null)
    {
        $this->tree->createNode($id, $label, $order);

        return $this;
    }

    /**
     * @return static
     */
    public function addChildNode(string $parentNodeId, $id, $label = null, int $order = null)
    {
        if ($parentNode = $this->tree->findNode($parentNodeId, true)) {
            $parentNode->createChild($id, $label, $order);
        }

        return $this;
    }

    /**
     * @return static
     */
    public function addSettingItem(string $parentNodeId, $widget, $settingKey, int $order = null)
    {
        $node = $this->tree->findNode($parentNodeId, true);
        if ($node && $setting = $this->findSettingByKey($settingKey)) {
            $node->createSettingItem($widget, $setting['object'], $setting['value'], $order);
        }

        return $this;
    }

    /**
     * @return static
     */
    public function addPreferenceItem(string $parentNodeId, $widget, $preferenceKey, int $order = null)
    {
        $node = $this->tree->findNode($parentNodeId, true);
        if ($node && $preference = $this->findPreferenceByKey($preferenceKey)) {
            $node->createPreferenceItem($widget, $preference['object'], $preference['value'], $order);
        }

        return $this;
    }

    protected function loadValues()
    {
        if (!$this->settings) {
            $this->settings = $this->settingRepository->all();
        }

        if (!$this->preferences) {
            $this->preferences = $this->preferenceRepository->all();
        }

        foreach ($this->tree->nodes() as $node) {
            $this->loadItemValues($node);
        }
    }

    /**
     * @param string $key
     *
     * @return array|null
     */
    protected function findSettingByKey(string $key)
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

        return null;
    }

    /**
     * @param string $key
     *
     * @return array|null
     */
    protected function findPreferenceByKey(string $key)
    {
        if (!$this->preferences && !$this->lazyLoad) {
            $this->preferences = $this->preferenceRepository->all();
        }

        if ($preference = $this->preferencesRegistry->get($key)) {
            return [
                'object' => $preference,
                'value'  => $this->preferences[$key] ?? $preference->default()
            ];
        }

        return null;
    }

    private function loadItemValues(Node $node, $recursive = true)
    {
        /** @var BaseItem $item */
        foreach ($node->items() as $item) {
            switch ($item->getType()->value()) {
                case CogType::SETTING:
                    $item->setValue($this->settings[$item->getKey()] ?? $item->getDefaultValue());
                    break;
                case CogType::PREFERENCE:
                    $item->setValue($this->preferences[$item->getKey()] ?? $item->getDefaultValue());
                    break;
            }
        }

        if ($recursive) {
            foreach ($node->children() as $child) {
                $this->loadItemValues($child);
            }
        }
    }
}
