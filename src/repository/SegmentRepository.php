<?php

namespace Audiens\AppnexusClient\repository;

use Audiens\AppnexusClient\CachableTrait;
use Audiens\AppnexusClient\CacheableInterface;
use Audiens\AppnexusClient\entity\Segment;
use Audiens\AppnexusClient\exceptions\RepositoryException;
use Doctrine\Common\Cache\Cache;
use GuzzleHttp\ClientInterface;

class SegmentRepository implements CacheableInterface
{

    use CachableTrait;

    public const BASE_URL         = 'https://api.adnxs.com/segment/';
    public const SANDBOX_BASE_URL = 'http://api-test.adnxs.com/segment/';
    public const CACHE_NAMESPACE  = 'appnexus_segment_repository_find_all';
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

    public function add(Segment $segment): RepositoryResponse
    {
        $compiledUrl = $this->baseUrl.$this->memberId;

        $payload = [
            'segment' => $segment->toArray(),
        ];

        $response = $this->client->request('POST', $compiledUrl, ['body' => json_encode($payload)]);

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        if ($repositoryResponse->isSuccessful()) {
            $stream          = $response->getBody();
            $responseContent = json_decode($stream->getContents(), true);
            $stream->rewind();

            if (!isset($responseContent['response']['segment']['id'])) {
                throw RepositoryException::wrongFormat(serialize($responseContent));
            }

            $segment->setId($responseContent['response']['segment']['id']);
        }

        return $repositoryResponse;
    }

    public function remove($id): RepositoryResponse
    {
        $compiledUrl = $this->baseUrl.$this->memberId.'/'.$id;

        $response = $this->client->request('DELETE', $compiledUrl);

        return RepositoryResponse::fromResponse($response);
    }

    public function update(Segment $segment): RepositoryResponse
    {
        if (!$segment->getId()) {
            throw RepositoryException::missingId($segment);
        }

        $compiledUrl = $this->baseUrl.$this->memberId.'/'.$segment->getId();

        $payload = [
            'segment' => $segment->toArray(),
        ];

        $response = $this->client->request('PUT', $compiledUrl, ['body' => json_encode($payload)]);

        return RepositoryResponse::fromResponse($response);
    }

    public function findOneById($id): ?Segment
    {
        $compiledUrl = $this->baseUrl.$this->memberId.'/'.$id;

        $response = $this->client->request('GET', $compiledUrl);

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        if (!$repositoryResponse->isSuccessful()) {
            return null;
        }

        $stream          = $response->getBody();
        $responseContent = json_decode($stream->getContents(), true);
        $stream->rewind();

        return Segment::fromArray($responseContent['response']['segment']);
    }

    public function findAll($start = 0, $maxResults = 100): ?array
    {
        $cacheKey = self::CACHE_NAMESPACE.sha1($this->memberId.$start.$maxResults);

        if ($this->cache->contains($cacheKey)) {
            return $this->cache->fetch($cacheKey);
        }

        $compiledUrl = $this->baseUrl.$this->memberId."?start_element=$start&num_elements=$maxResults";

        $response = $this->client->request('GET', $compiledUrl);

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        if (!$repositoryResponse->isSuccessful()) {
            throw RepositoryException::failed($repositoryResponse);
        }

        $stream          = $response->getBody();
        $responseContent = json_decode($stream->getContents(), true);
        $stream->rewind();

        $result = [];

        if (!$responseContent['response']['segments']) {
            $responseContent['response']['segments'] = [];
        }

        foreach ($responseContent['response']['segments'] as $segmentArray) {
            $result[] = Segment::fromArray($segmentArray);
        }

        $this->cache->save($cacheKey, $result, self::CACHE_EXPIRATION);

        return $result;
    }
}
