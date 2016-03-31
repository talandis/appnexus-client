<?php

namespace Audiens\AppnexusClient\authentication;

use Audiens\AppnexusClient\exceptions\AuthException;

/**
 * Class AuthStrategyInterface
 */
interface AuthStrategyInterface
{

    /**
     * @param string     $username
     * @param string     $password
     * @param bool|false $cache
     *
     * @throws AuthException
     * @return string the token
     */
    public function authenticate($username, $password, $cache = true);

    /**
     * @return string
     */
    public function getSlug();
}
