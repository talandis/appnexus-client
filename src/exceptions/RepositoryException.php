<?php

namespace Audiens\AppnexusClient\exceptions;

use Audiens\AppnexusClient\entity\Segment;
use Audiens\AppnexusClient\entity\SegmentBilling;
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
     * @return RepositoryException
     */
    public static function missingSegmentBillingContent()
    {
        return new self('Response returned an empty segment-billing-category');
    }

    /**
     * @param Segment $segment
     *
     * @return self
     */
    public static function missingId($segment)
    {
        return new self('Missing segment id for '.serialize($segment->getCode()));
    }

    /**
     * @param SegmentBilling $segment
     *
     * @return self
     */
    public static function missingSegmentBillingId($segment)
    {
        return new self('Missing segment billing id for '.serialize($segment->getId()));
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

    /**
     * @param string $reason
     *
     * @return self
     */
    public static function genericFailed($reason)
    {
        return new self($reason);
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
