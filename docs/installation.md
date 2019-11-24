# Installation

> Minimum requirements (as of v1.2) are PHP 7.1 and Laravel 5.5

Install with composer:

```bash
composer require konekt/gears
```

The service provider and the aliases (facades) get registered automatically with Laravel 5.5.

If you want to register the package manually, add the following lines to `config/app.php`:

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
