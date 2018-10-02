<?php

namespace Audiens\AppnexusClient\repository;

use Audiens\AppnexusClient\CachableTrait;
use Audiens\AppnexusClient\CacheableInterface;
use Audiens\AppnexusClient\entity\SegmentBilling;
use Audiens\AppnexusClient\exceptions\RepositoryException;
use Doctrine\Common\Cache\Cache;
use GuzzleHttp\ClientInterface;

class SegmentBillingRepository implements CacheableInterface
{
    use CachableTrait;

    public const BASE_URL         = 'https://api.adnxs.com/segment-billing-category';
    public const SANDBOX_BASE_URL = 'http://api-test.adnxs.com/segment-billing-category';
    public const CACHE_NAMESPACE  = 'appnexus_segment_billing_repository_find_all';
    public const CACHE_EXPIRATION = 3600;

    /** @var ClientInterface */
    protected $client;

    /** @var  int */
    protected $memberId;

    /** @var  Cache */
    protected $cache;

    /** @var  string */
    protected $baseUrl;

    public function __construct(ClientInterface $client, Cache $cache, int $memberId)
    {
        $this->client   = $client;
        $this->cache    = $cache;
        $this->memberId = $memberId;
        $this->baseUrl  = self::BASE_URL;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function add(SegmentBilling $segmentBilling): RepositoryResponse
    {
        $compiledUrl = $this->baseUrl.'?member_id='.$this->memberId;

        $payload = [
            'segment-billing-category' => $segmentBilling->toArray(),
        ];

        $response = $this->client->request('POST', $compiledUrl, ['body' => json_encode($payload)]);

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        if ($repositoryResponse->isSuccessful()) {
            $stream          = $response->getBody();
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

    public function update(SegmentBilling $segmentBilling): RepositoryResponse
    {
        if (!$segmentBilling->getId()) {
            throw RepositoryException::missingSegmentBillingId($segmentBilling);
        }

        $compiledUrl = $this->baseUrl.'?member_id='.$this->memberId;

        $payload = [
            'segment-billing-category' => $segmentBilling->toArray(),
        ];

        $response = $this->client->request('PUT', $compiledUrl, ['body' => json_encode($payload)]);

        return RepositoryResponse::fromResponse($response);
    }

    public function findOneBySegmentId($segmentId): ?SegmentBilling
    {
        $compiledUrl = $this->baseUrl.'?member_id='.$this->memberId.'&segment_id='.$segmentId;

        $response = $this->client->request('GET', $compiledUrl);

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        if (!$repositoryResponse->isSuccessful()) {
            throw RepositoryException::failed($repositoryResponse);
        }

        $stream          = $response->getBody();
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

    public function findAll($start = 0, $maxResults = 100): ?array
    {
        $cacheKey = self::CACHE_NAMESPACE.sha1($this->memberId.$start.$maxResults);

        if ($this->cache->contains($cacheKey)) {
            return $this->cache->fetch($cacheKey);
        }

        $compiledUrl = $this->baseUrl.'?member_id='.$this->memberId.'&start_element='.$start.'&num_elements='.$maxResults;

        $response = $this->client->request('GET', $compiledUrl);

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        if (!$repositoryResponse->isSuccessful()) {
            throw RepositoryException::failed($repositoryResponse);
        }

        $stream          = $response->getBody();
        $responseContent = json_decode($stream->getContents(), true);
        $stream->rewind();

        $result = [];

        if (!$responseContent['response']['segment-billing-categories']) {
            $responseContent['response']['segment-billing-categories'] = [];
        }

        foreach ($responseContent['response']['segment-billing-categories'] as $segmentBillingArray) {
            $result[] = SegmentBilling::fromArray($segmentBillingArray);
        }

        $this->cache->save($cacheKey, $result, self::CACHE_EXPIRATION);

        return $result;
    }

    public function remove($id)
    {
        $compiledUrl = $this->baseUrl.'?member_id='.$this->memberId.'&id='.$id;

        $response = $this->client->request('DELETE', $compiledUrl);

        return RepositoryResponse::fromResponse($response);
    }
}
