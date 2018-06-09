# Defining Preferences

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

**Next**: [Reading & Writing Values &raquo;](reading-and-writing-values.md)
