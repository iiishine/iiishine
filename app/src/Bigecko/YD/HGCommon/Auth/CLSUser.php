<?php namespace Bigecko\YD\HGCommon\Auth;

use Illuminate\Auth\GenericUser;

/**
 * User class for laravel auth system.
 */
class CLSUser extends GenericUser
{

    public function getAuthIdentifier()
    {
        return $this->attributes['MEMBER_ID'];
    }

    public function getAuthPassword()
    {
        return null;
    }
}
