<?php

namespace Audiens\AppnexusClient\repository;

use Audiens\AppnexusClient\entity\Segment;
use Audiens\AppnexusClient\exceptions\RepositoryException;
use Doctrine\Common\Cache\Cache;
use GeneratedHydrator\Configuration;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

/**
 * Class SegmentRepository
 */
class SegmentRepository
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

    const CACHE_NAMESPACE  = 'appnexus_segment_repository_find_all';
    const CACHE_EXPIRATION = 3600;

    /**
     * SegmentRepository constructor.
     *
     * @param ClientInterface $client
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
            'segment' => [
                'active' => $segment->isActive(),
                'description' => $segment->getDescription(),
                'member_id' => $segment->getMemberId(),
                'code' => $segment->getCode(),
                'provider' => $segment->getProvider(),
                'price' => $segment->getPrice(),
                'short_name' => $segment->getName(),
                'expire_minutes' => $segment->getExpireMinutes(),
                'category' => $segment->getCategory(),
                'last_activity' => $segment->getLastActivity()->getTimestamp(),
                'enable_rm_piggyback' => $segment->isEnableRmPiggyback(),
            ],
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
     * @return Segment|null
     */
    public function findOneById($id, $memberId)
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
     * @return Segment|null
     */
    public function findAll($memberId, $start = 0, $maxResults = 100)
    {

        if ($this->isCacheEnabled()) {
            $cacheKey = self::CACHE_NAMESPACE.sha1($memberId.$start.$maxResults);
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

    /**
     * @param boolean $cacheEnabled
     */
    public function setCacheEnabled($cacheEnabled)
    {
        $this->cacheEnabled = $cacheEnabled;
    }


}
