<?php

namespace Audiens\AppnexusClient\exceptions;

/**
 * Class RepositoryException
 */
class RepositoryException extends \Exception
{

    /**
     * @param $responseContent
     *
     * @return RepositoryException
     */
    public static function wrongFormat($responseContent)
    {
        return new self($responseContent);
    }
}
