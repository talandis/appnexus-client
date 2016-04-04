<?php

namespace Audiens\AppnexusClient\service;

use Audiens\AppnexusClient\Auth;
use Audiens\AppnexusClient\CacheableInterface;
use Audiens\AppnexusClient\entity\UploadJob;
use Audiens\AppnexusClient\entity\UploadTicket;
use Audiens\AppnexusClient\entity\UploadJobStatus;
use Audiens\AppnexusClient\exceptions\UploadException;
use Audiens\AppnexusClient\repository\RepositoryResponse;
use Doctrine\Common\Cache\Cache;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

/**
 * Class UserSegmentRepository
 */
class UserUpload implements CacheableInterface
{

    const BASE_URL = 'http://api.adnxs.com/batch-segment';

    /** @var  \SplQueue */
    protected $userSegments;

    /** @var Client|Auth */
    protected $client;

    /** @var  int */
    protected $memberId;

    /** @var  Cache */
    protected $cache;

    /** @var bool */
    protected $cacheEnabled;

    const CACHE_NAMESPACE = 'appnexus_segment_user_upload';

    const CACHE_EXPIRATION = 3600;

    /**
     * SegmentRepository constructor.
     *
     * @param ClientInterface $client
     * @param Cache|null      $cache
     */
    public function __construct(ClientInterface $client, Cache $cache = null)
    {
        $this->client = $client;
        $this->cache = $cache;
        $this->cacheEnabled = $cache instanceof Cache;

    }

    /**
     * @param $fileAsString
     * @param $memberId
     *
     * @return UploadJobStatus
     * @throws \Exception
     */
    public function upload($memberId, $fileAsString)
    {

        $tempFile = tmpfile();
        fwrite($tempFile, $fileAsString);
        fseek($tempFile, 0);

        $job = $this->getUploadTicket($memberId);

        $response = $this->client->request('POST', $job->getUploadUrl(), ['body' => $tempFile]);

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        if (!$repositoryResponse->isSuccessful()) {
            throw UploadException::failed($repositoryResponse);
        }

        return $this->getJobStatus($job);

    }

    /**
     * @param $memberId
     *
     * @return UploadTicket
     * @throws \Exception
     */
    public function getUploadTicket($memberId)
    {

        $compiledUrl = self::BASE_URL.'?member_id='.$memberId;

        $response = $this->client->request('POST', $compiledUrl);

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        if (!$repositoryResponse->isSuccessful()) {
            throw UploadException::failed($repositoryResponse);
        }

        if (!isset($repositoryResponse->getResponseAsArray()['response']['batch_segment_upload_job'])) {
            throw UploadException::missingIndex('response->batch_segment_upload_job');
        }

        $uploadJob = UploadTicket::fromArray(
            $repositoryResponse->getResponseAsArray()['response']['batch_segment_upload_job']
        );

        return $uploadJob;

    }

    /**
     * @param UploadTicket $uploadTicket
     *
     * @return UploadJob $uploadJob
     * @throws \Exception
     */
    public function getJobStatus(UploadTicket $uploadTicket)
    {

        $compiledUrl = self::BASE_URL."?member_id={$uploadTicket->getMemberId()}&job_id={$uploadTicket->getJobId()}";

        $response = $this->client->request('GET', $compiledUrl);

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        if (!$repositoryResponse->isSuccessful()) {
            throw UploadException::failed($repositoryResponse);
        }

        if (!isset($repositoryResponse->getResponseAsArray()['response']['batch_segment_upload_job'][0])) {
            throw UploadException::missingIndex('response->batch_segment_upload_job->0');
        }

        $uploadJobStatus = UploadJobStatus::fromArray(
            $repositoryResponse->getResponseAsArray()['response']['batch_segment_upload_job'][0]
        );

        return $uploadJobStatus;

    }


    /**
     * @param     $memberId
     * @param int $start
     * @param int $maxResults
     *
     * @return UploadJobStatus[]
     * @throws \Exception
     */
    public function getUploadHistory($memberId, $start = 0, $maxResults = 100)
    {

        $compiledUrl = self::BASE_URL."?member_id=$memberId&start_element=$start&num_elements=$maxResults";

        $response = $this->client->request('GET', $compiledUrl);

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        if (!$repositoryResponse->isSuccessful()) {
            throw UploadException::failed($repositoryResponse);
        }

        if (!isset($repositoryResponse->getResponseAsArray()['response']['batch_segment_upload_job'][0])) {
            throw UploadException::missingIndex('response->batch_segment_upload_job->0');
        }

        $uploadStatuses = [];

        $responseAsArray = $repositoryResponse->getResponseAsArray();

        foreach ($responseAsArray['response']['batch_segment_upload_job'] as $response) {
            $uploadStatuses[] = UploadJobStatus::fromArray($response);
        }

        return $uploadStatuses;

    }

    /**
     * @return boolean
     */
    public function isCacheEnabled()
    {
        return $this->cacheEnabled;
    }

    public function disableCache()
    {
        $this->cacheEnabled = false;
    }

    public function enableCache()
    {
        $this->cacheEnabled = true;
    }
}
