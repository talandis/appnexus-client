<?php

namespace Audiens\AppnexusClient\exceptions;

use Audiens\AppnexusClient\entity\MemberDataSharing;
use Audiens\AppnexusClient\entity\Segment;
use Audiens\AppnexusClient\entity\SegmentBilling;
use Audiens\AppnexusClient\repository\RepositoryResponse;

class RepositoryException extends \Exception
{

    public static function wrongFormat(string $responseContent): self
    {
        return new self($responseContent);
    }

    public static function missingSegmentBillingContent(): self
    {
        return new self('Response returned an empty segment-billing-category');
    }

    public static function missingId(Segment $segment): self
    {
        return new self('Missing segment id for '.serialize($segment->getName()));
    }

    public static function missingMemberDataSharingId(MemberDataSharing $memberDataSharing): self
    {
        return new self('Missing Member data sharing id for '.serialize($memberDataSharing));
    }

    public static function missingSegmentBillingId(SegmentBilling $segmentsegmentBilling): self
    {
        return new self('Missing segment billing id for '.serialize($segmentsegmentBilling->getId()));
    }

    public static function failed(RepositoryResponse $repositoryResponse): self
    {
        return new self('Failed call: '.$repositoryResponse->getError()->getError());
    }

    public static function genericFailed(string $reason): self
    {
        return new self($reason);
    }

    public static function missingIndex(string $missingIndex): self
    {
        return new self('Invalid reposnse missing: '.$missingIndex);
    }
}
