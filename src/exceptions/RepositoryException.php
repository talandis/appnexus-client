<?php

namespace Audiens\AppnexusClient\exceptions;

use Audiens\AppnexusClient\entity\Segment;
use Audiens\AppnexusClient\repository\RepositoryResponse;

/**
 * Class RepositoryException
 */
class RepositoryException extends \Exception
{

    /**
     * @param $responseContent
     *
     * @return self
     */
    public static function wrongFormat($responseContent)
    {
        return new self($responseContent);
    }

    /**
     * @param Segment $segment
     *
     * @return self
     */
    public static function missingId(Segment $segment)
    {
        return new self('Missing segment id for '.serialize($segment->getCode()));
    }

    /**
     * @param RepositoryResponse $repositoryResponse
     *
     * @return self
     */
    public static function failed(RepositoryResponse $repositoryResponse)
    {
        return new self('Failed call: '.$repositoryResponse->getError()->getError());
    }

    /**+
     * @param $missingIndex
     *
     * @return self
     */
    public static function missingIndex($missingIndex)
    {
        return new self('Invalid reposnse missing: '. $missingIndex);
    }
}
