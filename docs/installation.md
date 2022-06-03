# Installation

> Minimum requirements (as of v1.10) are PHP 8.0 and Laravel 8.22

Install with composer:

```bash
composer require konekt/gears
```

The service provider and the aliases (facades) get registered automatically with Laravel.

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

**Next**: [Configuration &raquo;](configuration.md)
