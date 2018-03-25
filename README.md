# Settings & Preferences In Laravel Applications


[![Travis Build Status](https://img.shields.io/travis/artkonekt/gears.svg?style=flat-square)](https://travis-ci.org/artkonekt/gears)
[![Packagist Stable Version](https://img.shields.io/packagist/v/konekt/gears.svg?style=flat-square&label=stable)](https://packagist.org/packages/konekt/gears)
[![StyleCI](https://styleci.io/repos/125667334/shield?branch=master)](https://styleci.io/repos/125667334)
[![Packagist downloads](https://img.shields.io/packagist/dt/konekt/gears.svg?style=flat-square)](https://packagist.org/packages/konekt/gears)
[![MIT Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE)

This Laravel package allows you to manage and save/retrieve settings and preferences in your Laravel application.

- **Settings** are user defined values that **apply to the application**.
- **Preferences** are user defined values that **apply to a specific user**.

**Setting examples:**

- API keys,
- Enable or disable features,
- Account related data (Billing data, plan, etc).

**Preference examples:**

- UI preferences like color scheme, font size, etc,
- Language,
- Timezone.

Settings and preferences are being managed separately. Values (by default) are being saved to the
database (`settings` and `preferences` tables) and are cached with the
[configured cache](https://laravel.com/docs/5.6/cache) for your application.

The backend for storing the settings can be completely replaced, so it is possible to store them in
anywhere else like MongoDB, ElasticSearch, Firebase, DynamoDB, S3, etc.

## Installation

> Minimum requirements are PHP 7.0 and Laravel 5.4

Install with composer:

```bash
composer require konekt/gears
```

The service provider and the aliases (facades) get registered automatically with Laravel 5.5 and higher.

**For Laravel 5.4** you need to register the package manually:

```php
// config/app.php

'providers' => [
    // ...
    Konekt\Gears\Providers\GearsServiceProvider::class,
],
//...
'aliases' => [
    // ...
    'Settings' => Konekt\Gears\Facades\Settings::class,
    'Preferences' => Konekt\Gears\Facades\Preferences::class,
]
```

## Defining Settings

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

$settingsRegistry->add(new SimpleSetting('theme', 'dark', ['dark', 'light']));
```

## Defining Preferences

```php
use \Konekt\Gears\Defaults\SimplePreference;
/** @var \Konekt\Gears\Registry\PreferencesRegistry $prefsRegistry */
$prefsRegistry = app('gears.preferences_registry');

// Simplest way, adding only by key:
$prefsRegistry->addByKey('secondary_email');

// Defining a default ('en'):
$prefsRegistry->add(new SimplePreference('language', 'en'));

// Defining a default and a list of available options:
$prefsRegistry->add(new SimplePreference('color_scheme', 'yellow', ['green', 'red', 'yellow']));
```

## Saving And Retrieving Settings

The easiest way is using the facade:

```php
use Konekt\Gears\Facades\Settings;

// Don't forget, the setting needs to be registered first:
app('gears.settings_registry')->addByKey('mailgun.api_key');

Settings::set('mailgun.api_key', '123456789abcdef');
echo Settings::get('mailgun.api_key');
// '123456789abcdef'
```

If you don't prefer using facades you can get the service from the container:

```php
$settings = app('gears.settings');
$settings->set('mailgun.api_key', 'fbcdef');
echo $settings->get('mailgun.api_key');
// fbcdef
```

> **Note:** `app('gears.settings')` and the `Settings` facade refer to the same instance.

#### Setting/Getting Multiple Settings At Once

```php
use Konekt\Gears\Defaults\SimpleSetting;
use Konekt\Gears\Facades\Settings;

$reg = app('gears.settings_registry');
$reg->addByKey('postmark.server_token');
$reg->addByKey('postmark.send_method');
$reg->add(new SimpleSetting('postmark.api_version', 'v3'));


// Saving multiple settings:
Settings::update([
   'postmark.server_token' => '9988776655',
   'postmark.send_method'  => 'smtp' 
]);

// Retrieving all settings:
var_dump(Settings::all());
// [
//     'postmark.server_token' => '988776655',
//     'postmark.send_method' => 'smtp',
//     'postmark.api_version' => 'v3',  <--- it returns the default if not explicitely set
// ]
```

## Removing Settings (Resetting Its Value)

If you remove a setting it's value will be reset to it's default value:

```php
use Konekt\Gears\Defaults\SimpleSetting;
use Konekt\Gears\Facades\Settings;

// Define a setting without default value:
app('gears.settings_registry')->addByKey('reports_to');

Settings::set('reports_to', 'me@where.com');
echo Settings::get('reports_to');
// 'me@where.com'
Settings::forget('reports_to');
var_dump(Settings::get('reports_to'));
// NULL

// Define a setting having a default value:
app('gears.settings_registry')->add(new SimpleSetting('lang', 'en'));

Settings::set('lang', 'nl');
echo Settings::get('lang');
// 'nl'
Settings::forget('lang');
echo Settings::get('lang');
// 'en'
```

It is possible to reset multiple values at once:

```php
use Konekt\Gears\Facades\Settings;

app('gears.settings_registry')->addByKey('billing_name');
app('gears.settings_registry')->addByKey('billing_address');

Settings::set('billing_name', 'My Company');
Settings::set('billing_address', 'Bernauer Str. 12, Berlin, Germany');

var_dump(Settings::all());
// [
//     'billing_name' => 'My Company',
//     'billing_address' => 'Bernauer Str. 12, Berlin, Germany'
// ]

Settings::delete(['billing_name', 'billing_address']);

var_dump(Settings::all());
// [
//     'billing_name' => NULL,
//     'billing_address' => NULL
// ]
```

## Saving And Retrieving Preferences

```php
use App\User;
use Illuminate\Support\Facades\Auth;
use Konekt\Gears\Facades\Preferences;

// Save preference for a user
$userId = 1;
Preferences::set('color_scheme', 'green', $userId);
echo Preferences::get('color_scheme', $userId);
// 'green'

// You can also pass a user object:
$user = User::find(1);
Preferences::set('color_scheme', 'green', $user);
echo Preferences::get('color_scheme', $user);
// 'green'

// If you don't pass a user, then Auth::user() gets picked:
Preferences::set('color_scheme', 'purple');
echo Preferences::get('color_scheme', Auth::user());
// purple

// which equals to:
echo Preferences::get('color_scheme');
// purple

// Forget a setting:
Preferences::forget('color_scheme', $userId);
var_dump(Preferences::get('color_scheme', $userId));
// NULL
```

## Saving And Retrieving Multiple Preferences At Once

```php
use Konekt\Gears\Facades\Preferences;

Preferences::update([
   'color_scheme' => 'orange',
   'font' => 'Adelle Sans' 
], $userId);

var_dump(Preferences::all($userId));
// [
//     'color_scheme' => 'orange',
//     'font' => 'Adelle Sans',
// ]

// Reset multiple preferences at once:
Preferences::delete(['color_scheme', 'font'], $userId);
var_dump(Preferences::all($userId));
// [
//     'color_scheme' => NULL,
//     'font' => NULL,
// ]
```
