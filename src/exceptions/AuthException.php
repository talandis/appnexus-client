<?php

namespace Audiens\AppnexusClient\exceptions;

/**
 * Class AuthException
 */
class AuthException extends \Exception
{

    const DEFAULT_MESSAGE = "Something wrong with the autentication: ";

    /**
     * @param $reason
     *
     * @return AuthException
     */
    public static function authFailed($reason)
    {
        return new self(self::DEFAULT_MESSAGE.$reason, 0, null);
    }
}
