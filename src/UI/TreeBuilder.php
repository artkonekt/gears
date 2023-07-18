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

use Illuminate\Support\Facades\Auth;
use Konekt\Gears\Enums\CogType;
use Konekt\Gears\Registry\PreferencesRegistry;
use Konekt\Gears\Registry\SettingsRegistry;
use Konekt\Gears\Repository\PreferenceRepository;
use Konekt\Gears\Repository\SettingRepository;

class TreeBuilder
{
    protected Tree $tree;

    protected ?array $settingValuesCache = null;

    protected ?array $preferenceValuesCache = null;

    protected ?string $userIdOfPreferencesCache = null;

    protected SettingsRegistry $settingsRegistry;

    protected PreferencesRegistry $preferencesRegistry;

    /** @param bool $lazyLoad @deprecated as of v1.10 and has no effect */
    public function __construct(
        protected SettingRepository $settingRepository,
        protected PreferenceRepository $preferenceRepository,
        bool $lazy = true,
    ) {
        $this->tree = new Tree();

        $this->settingsRegistry = $this->settingRepository->getRegistry();
        $this->preferencesRegistry = $this->preferenceRepository->getRegistry();
        if (false === $lazy) {
            trigger_error('The `$lazy` parameter is deprecated in the TreeBuilder\s constructor, and has no effect', E_USER_DEPRECATED);
        }
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

    public function bustCache(): void
    {
        $this->settingValuesCache = null;
        $this->preferenceValuesCache = null;
        $this->userIdOfPreferencesCache = null;
    }

    protected function loadValues()
    {
        if ($this->needsToLoadPreferenceValues()) {
            $this->fetchSettingValues();
        }

        if ($this->needsToLoadPreferenceValues()) {
            $this->fetchPreferenceValues();
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
        if ($this->needsToLoadSettingValues(deferable: true)) {
            $this->fetchSettingValues();
        }

        if ($setting = $this->settingsRegistry->get($key)) {
            return [
                'object' => $setting,
                'value'  => $this->settingValuesCache[$key] ?? $setting->default(),
            ];
        }

        return null;
    }

    protected function findPreferenceByKey(string $key): ?array
    {
        if ($this->needsToLoadPreferenceValues(deferable: true)) {
            $this->fetchPreferenceValues();
        }

        if ($preference = $this->preferencesRegistry->get($key)) {
            return [
                'object' => $preference,
                'value'  => $this->preferenceValuesCache[$key] ?? $preference->default(),
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
                    $item->setValue($this->settingValuesCache[$item->getKey()] ?? $item->getDefaultValue());
                    break;
                case CogType::PREFERENCE:
                    $item->setValue($this->preferenceValuesCache[$item->getKey()] ?? $item->getDefaultValue());
                    break;
            }
        }

        foreach ($node->children() as $child) {
            $this->loadItemValues($child);
        }
    }

    private function needsToLoadSettingValues(bool $deferable = false): bool
    {
        return is_null($this->settingValuesCache) && !$deferable;
    }

    private function fetchSettingValues(): void
    {
        $this->settingValuesCache = $this->settingRepository->all();
    }

    private function needsToLoadPreferenceValues(bool $deferable = false): bool
    {
        if ($deferable) {
            return !is_null($this->userIdOfPreferencesCache) && $this->userIdOfPreferencesCache !== (string) Auth::id();
        }

        return is_null($this->preferenceValuesCache) || $this->userIdOfPreferencesCache !== (string) Auth::id();
    }

    private function fetchPreferenceValues(): void
    {
        $this->preferenceValuesCache = $this->preferenceRepository->all();
        $this->userIdOfPreferencesCache = (string) Auth::id();
    }
}
