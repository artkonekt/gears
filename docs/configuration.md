# Configuration

The package configuration can be published by running:

```bash
php artisan vendor:publish --provider='Konekt\\Gears\\Providers\\GearsServiceProvider' --tag="config"
```

The following options can be configured:

| Key          | Value                                  | Default                                       | Details                                                  |
|:-------------|:---------------------------------------|:----------------------------------------------|:---------------------------------------------------------|
| `driver`     | Classname of the custom backend driver | `Konekt\Gears\Backend\Drivers\CachedDatabase` | See [Custom Backend](custom-backend.md) for more details |
| `migrations` | bool                                   | `true`                                        | If `false`, migrations will not be loaded*               |

> *If migrations are disabled, you can still publish them by invoking:<br> `php artisan
> vendor:publish --provider='Konekt\\Gears\\Providers\\GearsServiceProvider' --tag="migrations"`

