<?php

/**
 * Returns the value of a setting.
 *
 * @param string $key
 *
 * @return mixed
 */
if (!function_exists('setting')) {
    function setting(string $key)
    {
        return \Konekt\Gears\Facades\Settings::get($key);
    }
}

/**
 * Returns the value of a preference for a user.
 *
 * If user param is null, Auth::user() gets used
 *
 * @param string                                             $key
 * @param int|Illuminate\Contracts\Auth\Authenticatable|null $user
 *
 * @return mixed
 */
if (!function_exists('preference')) {
    function preference(string $key, $user = null)
    {
        return \Konekt\Gears\Facades\Preferences::get($key, $user);
    }
}
