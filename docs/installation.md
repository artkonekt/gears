# Installation

> Minimum requirements (as of v1.15) are PHP 8.3 and Laravel 11.46.2.  
> Earlier releases support older PHP (down to 7.0) and Laravel (down to 5.4) versions.

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
