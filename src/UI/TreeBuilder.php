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
    protected Tree $tree;

    protected SettingRepository $settingRepository;

    protected PreferenceRepository $preferenceRepository;

    protected SettingsRegistry $settingsRegistry;

    protected PreferencesRegistry $preferencesRegistry;

    /** @param bool $lazyLoad @deprecated as of v1.10 */
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
    }

    public function getTree(): Tree
    {
        $this->loadValues();

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
        if ($setting = $this->settingsRegistry->get($key)) {
            return [
                'object' => $setting,
                'value'  => $this->settingRepository->get($key),
            ];
        }

        return null;
    }

    protected function findPreferenceByKey(string $key): ?array
    {
        if ($preference = $this->preferencesRegistry->get($key)) {
            return [
                'object' => $preference,
                'value'  => $this->preferenceRepository->get($key),
            ];
        }

        return null;
    }

    private function loadItemValues(Node $node): void
    {
        /** @var BaseItem $item */
        foreach ($node->items() as $item) {
            switch ($item->getType()->value()) {
                case CogType::SETTING:
                    $item->setValue($this->settingRepository->get($item->getKey()));
                    break;
                case CogType::PREFERENCE:
                    $item->setValue($this->preferenceRepository->get($item->getKey()));
                    break;
            }
        }

        foreach ($node->children() as $child) {
            $this->loadItemValues($child);
        }
    }
}
