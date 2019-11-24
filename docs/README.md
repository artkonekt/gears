# Gears Documentation

This Laravel package allows you to manage settings and preferences in your Laravel application.

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

## Compatibility

| Laravel | Gears     |
|:--------|:----------|
| 5.4     | 0.9 - 1.1 |
| 5.5     | 0.9 - 1.2 |
| 5.6     | 0.9 - 1.2 |
| 5.7     | 1.1 - 1.2 |
| 5.8     | 1.2+      |
| 6.x     | 1.2+      |

---

**Next**: [Installation &raquo;](installation.md)


