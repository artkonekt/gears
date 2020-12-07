# Settings & Preferences In Laravel Applications

[![Tests](https://img.shields.io/github/workflow/status/artkonekt/gears/tests/master?style=flat-square)](https://github.com/artkonekt/gears/actions?query=workflow%3Atests)
[![Packagist Stable Version](https://img.shields.io/packagist/v/konekt/gears.svg?style=flat-square&label=stable)](https://packagist.org/packages/konekt/gears)
[![StyleCI](https://styleci.io/repos/125667334/shield?branch=master)](https://styleci.io/repos/125667334)
[![Packagist downloads](https://img.shields.io/packagist/dt/konekt/gears.svg?style=flat-square)](https://packagist.org/packages/konekt/gears)
[![MIT Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE)

This Laravel package allows you to manage and save/retrieve settings and preferences in your Laravel application.

- **Settings** are user defined values that **apply to the application**.
- **Preferences** are user defined values that **apply to a specific user**.

Settings and preferences are being managed separately. Values (by default) are being saved to the
database (`settings` and `preferences` tables) and are cached with the
[configured cache](https://laravel.com/docs/8.x/cache) for your application.

The backend for storing the settings can be completely replaced, so it is possible to store them in
anywhere else like MongoDB, ElasticSearch, Firebase, DynamoDB, S3, etc.

## Laravel Compatibility

| Laravel | Gears     |
|:--------|:----------|
| 5.4     | 0.9 - 1.1 |
| 5.5     | 0.9 - 1.2 |
| 5.6     | 0.9 - 1.2 |
| 5.7     | 1.1 - 1.2 |
| 5.8     | 1.2 - 1.5 |
| 6.x     | 1.2+      |
| 7.x     | 1.3+      |
| 8.x     | 1.5+      |


## Installation

> Minimum requirements (as of v1.6) are PHP 7.3 and Laravel 6

Install with composer:

```bash
composer require konekt/gears
```

The service provider and the aliases (facades) get registered automatically.

## Usage

Settings are identified by a key (string) and need to be registered in order to be used.

#### Register Settings

```php
/** @var \Konekt\Gears\Registry\SettingsRegistry $settingsRegistry */
$settingsRegistry = app('gears.settings_registry');

$settingsRegistry->addByKey('mailchimp.api_key');
```
#### Saving And Retrieving Settings

```php
use Konekt\Gears\Facades\Settings;

// using the facade:
Settings::set('mailchimp.api_key', '123456789abcdef');
echo Settings::get('mailchimp.api_key');
// '123456789abcdef'

// using the service from the container:
$settings = app('gears.settings');
$settings->set('mailchimp.api_key', 'fbcdef');
echo $settings->get('mailgun.api_key');
// fbcdef
```

To run the entire CI job suite locally: `act -P ubuntu-latest=shivammathur/node:latest`

There are many more options and possibilities detailed in the
[Documentation](https://konekt.dev/gears).

