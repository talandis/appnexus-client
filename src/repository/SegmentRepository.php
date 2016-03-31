<?php

namespace Audiens\AppnexusClient\repository;

use Audiens\AppnexusClient\CacheableInterface;
use Audiens\AppnexusClient\entity\Segment;
use Audiens\AppnexusClient\exceptions\RepositoryException;
use Doctrine\Common\Cache\Cache;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

/**
 * Class SegmentRepository
 */
class SegmentRepository implements CacheableInterface
{

    const BASE_URL = 'https://api.adnxs.com/segment/';

    /** @var Client */
    protected $client;

    /** @var  int */
    protected $memberId;

    /** @var  Cache */
    protected $cache;

    /** @var bool */
    protected $cacheEnabled;

    const CACHE_NAMESPACE = 'appnexus_segment_repository_find_all';

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
     * @param Segment $segment
     *
     * @return RepositoryResponse
     * @throws \Exception
     */
    public function add(Segment $segment)
    {

        $compiledUrl = self::BASE_URL.$segment->getMemberId();

        $payload = [
            'segment' => $segment->toArray(),
        ];

        $response = $this->client->request('POST', $compiledUrl, ['body' => json_encode($payload)]);

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        if ($repositoryResponse->isSuccessful()) {

            $stream = $response->getBody();
            $responseContent = json_decode($stream->getContents(), true);
            $stream->rewind();

            if (!(isset($responseContent['response']['segment']['id']))) {
                throw RepositoryException::wrongFormat(serialize($responseContent));
            }

            $segment->setId($responseContent['response']['segment']['id']);
        }

        return $repositoryResponse;

    }

    /**
     * @param $id
     * @param $memberId
     *
     * @return RepositoryResponse
     */
    public function remove($memberId, $id)
    {

        $compiledUrl = self::BASE_URL.$memberId.'/'.$id;

        $response = $this->client->request('DELETE', $compiledUrl);

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        return $repositoryResponse;

    }

    /**
     * @param Segment $segment
     *
     * @return RepositoryResponse
     * @throws \Exception
     */
    public function update(Segment $segment)
    {

        if (!$segment->getId()) {
            throw new \Exception('name me - missing id');
        }

        $compiledUrl = self::BASE_URL.$segment->getMemberId().'/'.$segment->getId();

        $payload = [
            'segment' => $segment->toArray(),
        ];

        $response = $this->client->request('PUT', $compiledUrl, ['body' => json_encode($payload)]);

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        return $repositoryResponse;

    }

    /**
     * @param $id
     * @param $memberId
     *
     * @return Segment|null
     */
    public function findOneById($memberId, $id)
    {

        $compiledUrl = self::BASE_URL.$memberId.'/'.$id;

        $response = $this->client->request('GET', $compiledUrl);

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        if (!$repositoryResponse->isSuccessful()) {
            return null;
        }

        $stream = $response->getBody();
        $responseContent = json_decode($stream->getContents(), true);
        $stream->rewind();

        return Segment::fromArray($responseContent['response']['segment']);

    }

    /**
     * @param     $memberId
     * @param int $start
     * @param int $maxResults
     *
     * @return array|mixed|null
     * @throws \Exception
     */
    public function findAll($memberId, $start = 0, $maxResults = 100)
    {

        $cacheKey = self::CACHE_NAMESPACE.sha1($memberId.$start.$maxResults);

        if ($this->isCacheEnabled()) {
            if ($this->cache->contains($cacheKey)) {
                return $this->cache->fetch($cacheKey);
            }
        }

        $compiledUrl = self::BASE_URL.$memberId."?start_element=$start&num_elements=$maxResults";

        $response = $this->client->request('GET', $compiledUrl);

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        if (!$repositoryResponse->isSuccessful()) {
            return null;
        }

        $stream = $response->getBody();
        $responseContent = json_decode($stream->getContents(), true);
        $stream->rewind();

        $result = [];

        if (!$responseContent['response']['segments']) {
            throw new \Exception('name me -missing index');
        }

        foreach ($responseContent['response']['segments'] as $segmentArray) {
            $result[] = Segment::fromArray($segmentArray);
        }


        if ($this->isCacheEnabled()) {
            $this->cache->save($cacheKey, $result, self::CACHE_EXPIRATION);
        }

        return $result;

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
