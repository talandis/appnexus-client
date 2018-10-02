<?php

namespace Audiens\AppnexusClient\authentication;

use Audiens\AppnexusClient\exceptions\AuthException;
use Doctrine\Common\Cache\Cache;
use GuzzleHttp\ClientInterface;

/**
 * Class SandboxStrategy
 */
class SandboxStrategy implements AuthStrategyInterface
{

    const NAME = 'sandbox_auth_strategy';

    const BASE_URL = 'http://api-test.adnxs.com/auth';

    const CACHE_NAMESPACE  = 'adnx_auth_token';
    const TOKEN_EXPIRATION = 110;

    /** @var Cache */
    protected $cache;

    /** @var ClientInterface  */
    protected $client;

    /**
     * @param ClientInterface $clientInterface
     * @param Cache      $cache
     */
    public function __construct(ClientInterface $clientInterface, Cache $cache)
    {
        $this->cache = $cache;
        $this->client = $clientInterface;
    }

    /**
     * @param string    $username
     * @param string    $password
     * @param bool|true $cache
     *
     * @return mixed
     * @throws AuthException
     */
    public function authenticate($username, $password, $cache = true)
    {

        $cacheKey = self::CACHE_NAMESPACE.sha1($username.$password.self::BASE_URL);

        if ($cache) {
            if ($this->cache->contains($cacheKey)) {
                return $this->cache->fetch($cacheKey);
            }
        }

        $payload = [
            'auth' => [
                'username' => $username,
                'password' => $password,
            ],
        ];

        $response = $this->client->request('POST', self::BASE_URL, ['body' => json_encode($payload)]);

        $content = $response->getBody()->getContents();
        $response->getBody()->rewind();

        $contentArray = json_decode($content, true);

        if (!isset($contentArray["response"]["token"])) {
            throw new AuthException($content);
        }

        $token = $contentArray["response"]["token"];

        if ($cache) {
            $this->cache->save($cacheKey, $token, self::TOKEN_EXPIRATION);
        }

        return $token;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return self::NAME;
    }
}
