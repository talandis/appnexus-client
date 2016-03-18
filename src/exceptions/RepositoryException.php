<?php

namespace Audiens\AppnexusClient\exceptions;

/**
 * Class RepositoryException
 */
class RepositoryException extends \Exception
{

    /**
     * @return RepositoryException
     */
    public static function wrongFormat($responseContent)
    {
        return new self($responseContent);
    }

}
