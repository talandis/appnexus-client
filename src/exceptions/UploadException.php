<?php

namespace Audiens\AppnexusClient\exceptions;

use Audiens\AppnexusClient\repository\RepositoryResponse;

/**
 * Class UploadException
 */
class UploadException extends \Exception
{

    /**
     * @param RepositoryResponse $repositoryResponse
     *
     * @return RepositoryException
     */
    public static function failed(RepositoryResponse $repositoryResponse)
    {
        return new self('Failed call: '.$repositoryResponse->getError()->getError());
    }

    /**+
     * @param $missingIndex
     *
     * @return RepositoryException
     */
    public static function missingIndex($missingIndex)
    {
        return new self('Invalid reposnse missing: '. $missingIndex);
    }
}
