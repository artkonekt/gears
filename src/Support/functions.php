<?php

/**
 * Returns the value of a setting.
 *
 * @param string $key
 *
 * @return mixed
 */
function setting(string $key)
{
    return \Konekt\Gears\Facades\Settings::get($key);
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
function preference(string $key, $user = null)
{
    return \Konekt\Gears\Facades\Preferences::get($key, $user);
}
