<?php
/**
 * Contains the User class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-03-20
 *
 */

namespace Konekt\Gears\Tests\Mocks;

use Illuminate\Foundation\Auth\User as BaseUser;

class User extends BaseUser
{
    protected $guarded = ['id'];
}
