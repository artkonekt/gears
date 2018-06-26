# Defining Preferences

Preferences need to be registered the same way as [settings](defining-settings.md).

> Register preferences is your application's `AppServiceProvider::boot()` method.

```php
use \Konekt\Gears\Defaults\SimplePreference;
/** @var \Konekt\Gears\Registry\PreferencesRegistry $prefsRegistry */
$prefsRegistry = app('gears.preferences_registry');

// Simplest way, adding only by key:
$prefsRegistry->addByKey('secondary_email');

// Defining a default ('en'):
$prefsRegistry->add(new SimplePreference('language', 'en'));

// Defining a default (yellow) and 3 available options:
$prefsRegistry->add(new SimplePreference('color_scheme', 'yellow', ['green', 'red', 'yellow']));
```

## Custom Preference Classes

In case you preference incorporates some custom logic you can define a custom class and register it as
a preference. The custom class must implement the `Konekt\Gears\Contracts\Perference` interface.

```php
namespace App\Preferences;

class CustomPreference implements \Konekt\Gears\Contracts\Preference
{
    public function key()
    {
        return 'custom_preference_key';
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
}
```

**Register it:**

```php
$prefsRegistry->add(new \App\Settings\CustomPreference());
```

**Next**: [Reading & Writing Values &raquo;](reading-and-writing-values.md)
