# Reading & Writing Values

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

## Helper Functions And Blade Templates

In Blade templates people rarely want OOP code, here you can utilize the helper functions:

```blade
<h1>{{ $product->title }}</>
<p>{{ $product->price }} {{ setting('shop.currency') }}</p>
```

## Setting/Getting Multiple Settings At Once

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

Settings::reset(['billing_name', 'billing_address']);

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

## Preferences in Blade Templates

In blade templates, preferences can be retrieved with the `preference()` helper function,
similarly to the `setting()` function:


```blade
<body @class(['is-dark' => preference('dark_mode')])>
</body>
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
Preferences::reset(['color_scheme', 'font'], $userId);
var_dump(Preferences::all($userId));
// [
//     'color_scheme' => NULL,
//     'font' => NULL,
// ]
```

**Next**: [Building UI &raquo;](building-ui.md)
