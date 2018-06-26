# Defining Settings

**Settings** are identified by a key (string) and **need to be registered** first in order to be used:

```php
/** @var \Konekt\Gears\Registry\SettingsRegistry $settingsRegistry */
$settingsRegistry = app('gears.settings_registry');

$settingsRegistry->addByKey('mailchimp.api_key');
$settingsRegistry->addByKey('mailchimp.list_id');
```

> The recommended place to register settings is your application's `AppServiceProvider::boot()` method.

## Setting Defaults

It is possible to define a default value for a setting:

```php
use \Konekt\Gears\Defaults\SimpleSetting;
/** @var \Konekt\Gears\Registry\SettingsRegistry $settingsRegistry */
$settingsRegistry = app('gears.settings_registry');

// Set default value to true:
$settingsRegistry->add(new SimpleSetting('use_annotations', true));
// Set default value to '/dashboard'
$settingsRegistry->add(new SimpleSetting('start_url', '/dashboard'));
```

## Possible Options

You can also set a list of possible options (useful for dropdowns):

```php
use \Konekt\Gears\Defaults\SimpleSetting;
/** @var \Konekt\Gears\Registry\SettingsRegistry $settingsRegistry */
$settingsRegistry = app('gears.settings_registry');

// Default theme is dark, available options are dark and light
$settingsRegistry->add(new SimpleSetting('theme', 'dark', ['dark', 'light']));
```

## Custom Setting Classes

In case you setting incorporates some custom logic you can define a custom class and register it as
a setting. The custom class must implement the `Konekt\Gears\Contracts\Setting` interface.

```php
namespace App\Settings;

class CustomSetting implements \Konekt\Gears\Contracts\Setting
{
    public function key()
    {
        return 'custom_setting_key';
    }

    public function default()
    {
        return 'Default';
    }

    // Whether the access to the setting is allowed (eg. for the current user)
    public function isAllowed()
    {
        return true;
    }

    // Use for dropdowns, radio buttons, etc
    public function options()
    {
        return ['Default', 'Custom'];
    }

    // Whether to synchronize the setting with Laravel Configuration
    public function syncWithConfig()
    {
        return false;
    }
}
```

**Register it:**

```php
$settingsRegistry->add(new \App\Settings\CustomSetting());
```

**Next**: [Defining Preferences &raquo;](defining-preferences.md)
