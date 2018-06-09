# Defining Settings

Settings are identified by a key (string) and need to be registered in order to be used:

```php
/** @var \Konekt\Gears\Registry\SettingsRegistry $settingsRegistry */
$settingsRegistry = app('gears.settings_registry');

$settingsRegistry->addByKey('mailchimp.api_key');
$settingsRegistry->addByKey('mailchimp.list_id');
```

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

You can also set a list of possible options (useful for dropdowns):

```php
use \Konekt\Gears\Defaults\SimpleSetting;
/** @var \Konekt\Gears\Registry\SettingsRegistry $settingsRegistry */
$settingsRegistry = app('gears.settings_registry');

// Default theme is dark, available options are dark and light
$settingsRegistry->add(new SimpleSetting('theme', 'dark', ['dark', 'light']));
```

**Next**: [Defining Preferences &raquo;](defining-preferences.md)
