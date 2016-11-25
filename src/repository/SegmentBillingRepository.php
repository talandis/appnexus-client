<?php

namespace Audiens\AppnexusClient\repository;

use Audiens\AppnexusClient\CachableTrait;
use Audiens\AppnexusClient\CacheableInterface;
use Audiens\AppnexusClient\entity\SegmentBilling;
use Audiens\AppnexusClient\exceptions\RepositoryException;
use Doctrine\Common\Cache\Cache;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

/**
 * Class SegmentBillingRepository
 */
class SegmentBillingRepository implements CacheableInterface
{
    use CachableTrait;

    const BASE_URL = 'https://api.adnxs.com/segment-billing-category';

    const SANDBOX_BASE_URL = 'http://api-test.adnxs.com/segment-billing-category';

    /** @var Client */
    protected $client;

    /** @var  int */
    protected $memberId;

    /** @var  Cache */
    protected $cache;

    /** @var  string */
    protected $baseUrl;

    const CACHE_NAMESPACE = 'appnexus_segment_billing_repository_find_all';

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
        $this->baseUrl = self::BASE_URL;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }


    /**
     * @param SegmentBilling $segmentBilling
     *
     * @return RepositoryResponse
     * @throws RepositoryException
     */
    public function add(SegmentBilling $segmentBilling)
    {

        $compiledUrl = $this->baseUrl.'?member_id='.$segmentBilling->getMemberId();

        $payload = [
            'segment-billing-category' => $segmentBilling->toArray(),
        ];


        $response = $this->client->request('POST', $compiledUrl, ['body' => json_encode($payload)]);

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        if ($repositoryResponse->isSuccessful()) {
            $stream = $response->getBody();
            $responseContent = json_decode($stream->getContents(), true);

            $stream->rewind();

            if (count($responseContent['response']['segment-billing-category']) == 0) {
                throw RepositoryException::missingSegmentBillingContent();
            }

            if (!(isset($responseContent['response']['segment-billing-category'][0]['id']))) {
                throw RepositoryException::wrongFormat(serialize($responseContent));
            }

            $segmentBilling->setId($responseContent['response']['segment-billing-category'][0]['id']);
        }

        return $repositoryResponse;
    }

    /**
     * @param SegmentBilling $segmentBilling
     *
     * @return RepositoryResponse
     * @throws RepositoryException
     */
    public function update(SegmentBilling $segmentBilling)
    {

        if (!$segmentBilling->getId()) {
            throw RepositoryException::missingSegmentBillingId($segmentBilling);
        }

        $compiledUrl = $this->baseUrl.'?member_id='.$segmentBilling->getMemberId();

        $payload = [
            'segment-billing-category' => $segmentBilling->toArray(),
        ];

        $response = $this->client->request('PUT', $compiledUrl, ['body' => json_encode($payload)]);

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        return $repositoryResponse;
    }

    /**
     * @param $memberId
     * @param $segmentId
     * @return SegmentBilling
     * @throws RepositoryException
     */
    public function findOneBySegmentId($memberId, $segmentId)
    {

        $compiledUrl = $this->baseUrl.'?member_id='.$memberId.'&segment_id='.$segmentId;


        $response = $this->client->request('GET', $compiledUrl);

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        if (!$repositoryResponse->isSuccessful()) {
            throw RepositoryException::failed($repositoryResponse);
        }

        $stream = $response->getBody();
        $responseContent = json_decode($stream->getContents(), true);
        $stream->rewind();

        if (!$responseContent['response']['segment-billing-categories']) {
            return null;
        }

        if (count($responseContent['response']['segment-billing-categories']) > 1) {
            throw RepositoryException::genericFailed('Expected only one results. Found '.count($responseContent['response']['segment-billing-categories']));
        }


        return SegmentBilling::fromArray($responseContent['response']['segment-billing-categories'][0]);
    }

    /**
     * @param     $memberId
     * @param int $start
     * @param int $maxResults
     *
     * @return SegmentBilling[]|null
     * @throws RepositoryException
     */
    public function findAll($memberId, $start = 0, $maxResults = 100)
    {

        $cacheKey = self::CACHE_NAMESPACE.sha1($memberId.$start.$maxResults);


        if ($this->isCacheEnabled()) {
            if ($this->cache->contains($cacheKey)) {
                return $this->cache->fetch($cacheKey);
            }
        }

        $compiledUrl = $this->baseUrl.'?member_id='.$memberId.'&start_element='.$start.'&num_elements='.$maxResults;


        $response = $this->client->request('GET', $compiledUrl);

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        if (!$repositoryResponse->isSuccessful()) {
            throw RepositoryException::failed($repositoryResponse);
        }

        $stream = $response->getBody();
        $responseContent = json_decode($stream->getContents(), true);
        $stream->rewind();


        $result = [];

        if (!$responseContent['response']['segment-billing-categories']) {
            $responseContent['response']['segment-billing-categories'] = [];
        }

        foreach ($responseContent['response']['segment-billing-categories'] as $segmentBillingArray) {
            $result[] = SegmentBilling::fromArray($segmentBillingArray);
        }

        if ($this->isCacheEnabled()) {
            $this->cache->save($cacheKey, $result, self::CACHE_EXPIRATION);
        }

        return $result;
    }

    /**
     * @param $memberId
     * @param $id
     *
     * @return RepositoryResponse
     */
    public function remove($memberId, $id)
    {

        $compiledUrl = $this->baseUrl.'?member_id='.$memberId.'&id='.$id;

        $response = $this->client->request('DELETE', $compiledUrl);

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        return $repositoryResponse;
    }
}
