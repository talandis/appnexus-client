<?php

namespace Audiens\AppnexusClient\repository;

use Audiens\AppnexusClient\CachableTrait;
use Audiens\AppnexusClient\CacheableInterface;
use Audiens\AppnexusClient\entity\Category;
use Audiens\AppnexusClient\exceptions\RepositoryException;
use Doctrine\Common\Cache\Cache;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

/**
 * Class CategoryRepository
 */
class CategoryRepository implements CacheableInterface
{

    use CachableTrait;

    const BASE_URL = 'https://api.adnxs.com/content-category/';

    const SANDBOX_BASE_URL = 'http://api-test.adnxs.com/content-category/';

    /** @var Client */
    protected $client;

    /** @var  int */
    protected $memberId;

    /** @var  Cache */
    protected $cache;

    /** @var  string */
    protected $baseUrl;

    const CACHE_NAMESPACE = 'appnexus_category_repository_find_all';

    const CACHE_EXPIRATION = 3600;

    /**
     * SegmentRepository constructor.
     *
     * @param ClientInterface $client
     * @param Cache|null $cache
     */
    public function __construct(ClientInterface $client, Cache $cache = null)
    {
        $this->client       = $client;
        $this->cache        = $cache;
        $this->cacheEnabled = $cache instanceof Cache;
        $this->baseUrl      = self::BASE_URL;
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
     * @return Category[]|null
     * @throws RepositoryException
     */
    public function findAll($start = 0, $maxResults = 100)
    {

        $cacheKey = self::CACHE_NAMESPACE . sha1($start . $maxResults);

        if ($this->isCacheEnabled()) {
            if ($this->cache->contains($cacheKey)) {
                return $this->cache->fetch($cacheKey);
            }
        }

        $compiledUrl = $this->baseUrl . "?start_element=$start&num_elements=$maxResults";

        $response = $this->client->request('GET', $compiledUrl);

        $repositoryResponse = RepositoryResponse::fromResponse($response);

        if (!$repositoryResponse->isSuccessful()) {
            throw RepositoryException::failed($repositoryResponse);
        }

        $stream          = $response->getBody();
        $responseContent = json_decode($stream->getContents(), true);
        $stream->rewind();

        $result = [];

        if (!$responseContent['response']['content_categories']) {
            $responseContent['response']['content_categories'] = [];
        }

        foreach ($responseContent['response']['content_categories'] as $segmentArray) {
            $result[] = Category::fromArray($segmentArray);
        }

        if ($this->isCacheEnabled()) {
            $this->cache->save($cacheKey, $result, self::CACHE_EXPIRATION);
        }

        return $result;
    }
}
