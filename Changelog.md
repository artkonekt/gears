# Changelog

## 1.7.0
##### 2020-12-07

- Added PHP 8 support
- Changed CI from travis to github actions

## 1.6.1
##### 2020-10-31

- Replaced the injection of `cache.store` to `cache` (CacheManager) in the CachedDatabase driver in
  to improve compatibility with packages manipulating the cache,
  eg. [Tenancy for Laravel](https://tenancyforlaravel.com/docs/v3/configuration#cache)

## 1.6.0
##### 2020-10-11

- Allow v3.0 enums
- Dropped Laravel 5 support
- Dropped PHP 7.2 support

## 1.5.0
##### 2020-09-12

- Added Laravel 8 Support

## 1.4.1
##### 2020-08-31

- Fixed possible conflict with `setting` and `preference` helper functions already defined elsewhere

## 1.4.0
##### 2020-06-20

- Migrations can be disabled and/or published
- Configuration can be published

## 1.3.0
##### 2020-03-13

- Added Laravel 7 Support
- Added PHP 7.4 Support
- Dropped PHP 7.1 Support

## 1.2.0
##### 2019-11-24

- Added Laravel 6.x and 5.8 Support
- Dropped Laravel 5.4 and PHP 7.0 support

## 1.1.0
##### 2018-11-01

- Callbacks can be passed as `$options` to SimpleSetting and SimplePreference constructors
- Proven to work with Laravel 5.7

## 1.0.0
##### 2018-08-11

- Same as RC2
- UI Documentation has been added
- README has been simplified

## 1.0.0-rc.2
##### 2018-07-06

- MySQL<5.7 and MariaDB compatibility fix (key length issues)
- Documentation updates

## 1.0.0-rc.1
##### 2018-06-09

- UI Tree Builder lazy loads settings/prefs
- Order of UI nodes and items can be set

# 0.9

## 0.9.2
##### 2018-05-27

- Laravel 5.4 compatibility fix

## 0.9.1
##### 2018-05-27

- Tree builder improvements
- DB backend timestamps are working
- `setting()` and `preference()` shortcut functions have been added

## 0.9.0
##### 2018-05-27

- First tagged version
- Settings, Preferences, Backend works
- UI tree is mostly functional, builder is WIP
- Doc is OK, except for UI part
