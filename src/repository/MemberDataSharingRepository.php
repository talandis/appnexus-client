<?php

namespace Audiens\AppnexusClient\repository;

use Audiens\AppnexusClient\CachableTrait;
use Audiens\AppnexusClient\CacheableInterface;
use Audiens\AppnexusClient\entity\MemberDataSharing;
use Audiens\AppnexusClient\entity\MemberDataSharingSegment;
use Audiens\AppnexusClient\exceptions\RepositoryException;
use Doctrine\Common\Cache\Cache;
use GuzzleHttp\ClientInterface;

class MemberDataSharingRepository implements CacheableInterface
{
    use CachableTrait;

    public const BASE_URL         = 'https://api.adnxs.com/member-data-sharing/';
    public const SANDBOX_BASE_URL = 'http://api-test.adnxs.com/member-data-sharing/';
    public const CACHE_NAMESPACE  = 'appnexus_member_data_sharing_find_all';
    public const CACHE_EXPIRATION = 3600;

    /** @var ClientInterface */
    protected $client;

    /** @var  int */
    protected $memberId;

    /** @var  Cache */
    protected $cache;

    /** @var  string */
    protected $baseUrl;

    /**
     * @param ClientInterface $client
     * @param Cache           $cache
     */
    public function __construct(ClientInterface $client, Cache $cache)
    {
        $this->client  = $client;
        $this->cache   = $cache;
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
     * @param int $start
     * @param int $maxResults
     *
     * @return array|MemberDataSharing[]
     * @throws RepositoryException
     */
    public function findAll($start = 0, $maxResults = 100)
    {
        $cacheKey = self::CACHE_NAMESPACE.sha1($start.$maxResults);

        if ($this->cache->contains($cacheKey)) {
            return $this->cache->fetch($cacheKey);
        }

        $compiledUrl = $this->baseUrl."?start_element=$start&num_elements=$maxResults";

        $response = $this->client->request('GET', $compiledUrl);

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        if (!$repositoryResponse->isSuccessful()) {
            throw RepositoryException::failed($repositoryResponse);
        }

        $stream          = $response->getBody();
        $responseContent = json_decode($stream->getContents(), true);
        $stream->rewind();

        $result = [];

        if (!$responseContent['response']['member_data_sharings']) {
            $responseContent['response']['member_data_sharings'] = [];
        }

        foreach ($responseContent['response']['member_data_sharings'] as $dataArray) {
            $result[] = MemberDataSharing::fromArray($dataArray);
        }

        $this->cache->save($cacheKey, $result, self::CACHE_EXPIRATION);

        return $result;
    }

    /**
     * @param int $buyerId
     * @param int $memberId
     *
     * @return array|MemberDataSharing[]
     * @throws RepositoryException
     */
    public function findByBuyerId($buyerId, $memberId)
    {
        $cacheKey = self::CACHE_NAMESPACE.sha1($buyerId.$memberId);

        if ($this->cache->contains($cacheKey)) {
            return $this->cache->fetch($cacheKey);
        }

        $compiledUrl = $this->baseUrl."?data_member_id=$memberId&buyer_member_id=$buyerId";

        $response = $this->client->request('GET', $compiledUrl);

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        if (!$repositoryResponse->isSuccessful()) {
            throw RepositoryException::failed($repositoryResponse);
        }

        $stream          = $response->getBody();
        $responseContent = json_decode($stream->getContents(), true);
        $stream->rewind();

        $result = [];

        if (!$responseContent['response']['member_data_sharings']) {
            $responseContent['response']['member_data_sharings'] = [];
        }

        foreach ($responseContent['response']['member_data_sharings'] as $dataArray) {
            $memberDataSharing = MemberDataSharing::fromArray($dataArray);

            $segments = [];

            if (!empty($memberDataSharing->getSegments() && count($memberDataSharing->getSegments()) > 0)) {
                foreach ($memberDataSharing->getSegments() as $segment) {
                    $segments[] = MemberDataSharingSegment::fromArray($segment);
                }
            }
            $memberDataSharing->setSegments($segments);

            $result[] = $memberDataSharing;
        }

        $this->cache->save($cacheKey, $result, self::CACHE_EXPIRATION);

        return $result;
    }

    /**
     * @param int $id
     *
     * @return array|MemberDataSharing[]
     */
    public function findById($id)
    {
        $cacheKey = self::CACHE_NAMESPACE.sha1((string)$id);

        if ($this->cache->contains($cacheKey)) {
            return $this->cache->fetch($cacheKey);
        }

        $compiledUrl = $this->baseUrl."$id";

        $response = $this->client->request('GET', $compiledUrl);

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        if (!$repositoryResponse->isSuccessful()) {
            throw RepositoryException::failed($repositoryResponse);
        }

        $stream          = $response->getBody();
        $responseContent = json_decode($stream->getContents(), true);
        $stream->rewind();

        $result = [];

        if (!$responseContent['response']['member_data_sharing']) {
            $responseContent['response']['member_data_sharing'] = [];
        }

        $memberDataSharing = MemberDataSharing::fromArray($responseContent['response']['member_data_sharing']);

        $segments = [];

        if (!empty($memberDataSharing->getSegments() && count($memberDataSharing->getSegments()) > 0)) {
            foreach ($memberDataSharing->getSegments() as $segment) {
                $segments[] = MemberDataSharingSegment::fromArray($segment);
            }
        }
        $memberDataSharing->setSegments($segments);

        $result[] = $memberDataSharing;

        $this->cache->save($cacheKey, $result, self::CACHE_EXPIRATION);

        return $result;
    }

    /**
     * @param MemberDataSharing $mSharing
     *
     * @return RepositoryResponse
     * @throws RepositoryException
     */
    public function add(MemberDataSharing $mSharing)
    {
        $compiledUrl = $this->baseUrl;

        $payload = [
            'member_data_sharing' => $mSharing->toArray(),
        ];

        if (!empty($mSharing->getSegments())) {
            $segmentToUpload = [];

            foreach ($mSharing->getSegments() as $segment) {
                $segmentToUpload[] = $segment->toArray();
            }

            $payload['member_data_sharing']['segments'] = $segmentToUpload;
        }

        $response = $this->client->request('POST', $compiledUrl, ['body' => json_encode($payload)]);

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        if ($repositoryResponse->isSuccessful()) {
            $stream          = $response->getBody();
            $responseContent = json_decode($stream->getContents(), true);
            $stream->rewind();

            if (!(isset($responseContent['response']['member_data_sharing']['id']))) {
                throw RepositoryException::wrongFormat(serialize($responseContent));
            }

            $mSharing->setId($responseContent['response']['member_data_sharing']['id']);
        }

        return $repositoryResponse;
    }

    /**
     * @param int                        $memberDataSharingId
     * @param MemberDataSharingSegment[] $segments
     *
     * @return RepositoryResponse
     */
    public function addSegmentsToMemberDataSharing($memberDataSharingId, $segments)
    {
        $compiledUrl = $this->baseUrl.$memberDataSharingId;

        /** @var MemberDataSharingSegment[] $mdObject */
        $mdObjectArray = $this->findById($memberDataSharingId);

        if (count($mdObjectArray) === 0) {
            throw new \RuntimeException('Could not find the selected member data sharing');
        }

        $segmentsOld = $mdObjectArray[0]->getSegments();

        $segmentToUpload = [];

        foreach ($segmentsOld as $sOld) {
            $segmentToUpload[] = $sOld->toArray();
        }

        foreach ($segments as $segment) {
            $segmentToUpload[] = $segment->toArray();
        }

        $payload = [
            'member_data_sharing' => [
                'segments' => $segmentToUpload,
            ],
        ];

        $response = $this->client->request('PUT', $compiledUrl, ['body' => json_encode($payload)]);

        return RepositoryResponse::fromResponse($response);
    }

    public function update(MemberDataSharing $memberDataSharing)
    {
        if (!$memberDataSharing->getId()) {
            throw RepositoryException::missingMemberDataSharingId($memberDataSharing);
        }

        $compiledUrl = $this->baseUrl.'?id='.$memberDataSharing->getId();

        $payload = [
            'member_data_sharing' => $memberDataSharing->toArray(),
        ];

        $response = $this->client->request('PUT', $compiledUrl, ['body' => json_encode($payload)]);

        return RepositoryResponse::fromResponse($response);
    }
}
