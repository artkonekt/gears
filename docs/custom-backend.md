# Custom Backend

The Settings Backend defines how the settings are persisted/retrieved.

The default Backend of the library saves settings to the database (`settings` and `preferences`
tables) and caches with your application's [configured cache](https://laravel.com/docs/7.x/cache).

To replace the backend the following steps are required:

1. Writing the custom backend class (has to implement the `Backend` interface).
2. Set the fully qualified classname in the config* under the `gears.driver` value.

> *See the [Configuration](configuration.md) section for more details.

## Custom Backend Example

```php
namespace App\Settings;

use Illuminate\Support\Collection;
use Konekt\Gears\Contracts\Backend;

class CustomBackend implements Backend
{
    public function allSettings(): Collection
    {
        // Returns all the saved settings in a Collection, where
        // the settings' names are the keys, and their values
        // are the respective values of the saved settings
    }

    public function allPreferences($userId): Collection
    {
        // Returns all the saved preferences for a given user
        // in a Collection, where the prefs' names are the
        // keys, and values are the user's saved prefs.        
    }

    public function getSetting(string $key)
    {
        // Returns the value for a specific setting identified by the key
    }

    public function getPreference($key, $userId)
    {
        // Returns the value for a specific preference
        // identified by the key, for the user that
        // is identified by the user id argument        
    }

    public function setSetting($key, $value)
    {
        // Save the value for a setting identified by the key
    }

    public function setPreference($key, $value, $userId)
    {
        // Save the value for a specific preference (key)
        // for a specific user, based on the user id.
    }

    public function setSettings(array $settings)
    {
        // Save the values of multiple settings at once,
        // where the settings array contains a set of
        // key/value pairs, keys are setting names
    }

    public function setPreferences(array $preferences, $userId)
    {
        // Set the values of multiple preferences for a given
        // user at once, where the preferences array holds
        // a set of key/value pairs; pref names as keys
    }

    public function removeSetting($key)
    {
        // Delete the value for a specific setting identified by the key        
    }

    public function removePreference($key, $userId)
    {
        // Delete the value for a specific
        // preference identified by the
        // key, for the given userid
    }

    public function removeSettings(array $keys)
    {
        // Delete values of multiple settings
        // at once where setting names are
        // the in the given keys array.
    }

    public function removePreferences(array $keys, $userId)
    {
        // Delete values of multiple preferences for a
        // given user at once, where the preference
        // names are the in the given keys array
    }
}
```

> For inspiration study the `Konekt\Gears\Backend\Drivers\Database` and
> `Konekt\Gears\Backend\Drivers\CachedDatabase` classes.

**Next**: [API Reference &raquo;](reference.md)
