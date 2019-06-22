# Gears - Settings & Preferences for Laravel Application

This Laravel package allows you to manage and save/retrieve settings and preferences in your Laravel application.

- **Settings** are user defined values that **apply to the application**.
- **Preferences** are user defined values that **apply to a specific user**.

Settings and preferences are being managed separately. Values (by default) are being saved to the
database (`settings` and `preferences` tables) and are cached with the
[configured cache](https://laravel.com/docs/5.8/cache) for your application.

The backend for storing the settings can be completely replaced, so it is possible to store them in
anywhere else like MongoDB, ElasticSearch, Firebase, DynamoDB, S3, etc.

## 1 Minute Intro

Settings are identified by a key (string) and need to be registered in order to be used.

#### Register Settings

```php
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

