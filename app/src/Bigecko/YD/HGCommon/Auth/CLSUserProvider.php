<?php namespace Bigecko\YD\HGCommon\Auth;

use Illuminate\Auth\UserProviderInterface;
use Illuminate\Auth\UserInterface;

/**
 * CLS user provider for laravel auth system.
 */

class CLSUserProvider implements UserProviderInterface {

    protected $session;

    public function __construct($session)
    {
        $this->session = $session;
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Auth\UserInterface|null
     */
    public function retrieveById($identifier) {
        $memberData = $this->session->get('member_data_' . $identifier);
        if (!$memberData) {
            return null;
        }

        return new CLSUser($memberData);
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Auth\UserInterface|null
     */
    public function retrieveByCredentials(array $credentials) {
        throw new \Exception('Not implemented');
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Auth\UserInterface  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(UserInterface $user, array $credentials) {
        throw new \Exception('Not implemented');
    }



}
