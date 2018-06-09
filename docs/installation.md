# Installation

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

**Next**: [Defining Settings &raquo;](defining-settings.md)
