<?php

namespace Audiens\AppnexusClient;

use Audiens\AppnexusClient\exceptions\AuthException;
use Doctrine\Common\Cache\Cache;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

/**
 * Class Auth
 *
 * see https://wiki.apnexus.com/display/adnexusdocumentation/Segment+Service
 *
 */
class Auth implements ClientInterface
{

    /** @var  Cache */
    protected $cache;

    /** @var  Client */
    protected $client;

    /** @var bool */
    protected $cacheEnabled = false;

    /** @var string */
    protected $token;

    /** @var string */
    protected $username;

    /** @var string */
    protected $password;

    const BASE_URL = 'https://api.adnxs.com/auth';

    const MEMBER_ID = 3847;

    const CACHE_NAMESPACE  = 'appnexus_auth_token';
    const TOKEN_EXPIRATION = 110;

    /**
     * Auth constructor.
     *
     * @param array           $username
     * @param                 $password
     * @param ClientInterface $clientInterface
     * @param Cache|null      $cache
     */
    public function __construct($username, $password, ClientInterface $clientInterface, Cache $cache = null)
    {
        $this->username = $username;
        $this->password = $password;

        $this->cacheEnabled = $cache instanceof Cache;
        $this->cache = $cache;

        $this->client = $clientInterface;

    }


    /**
     * @param string $method
     * @param null   $uri
     * @param array  $options
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function request($method, $uri = null, array $options = [])
    {
        $optionForToken = [
            'headers' => [
                'Authorization' => $this->autenticate(),
            ],
        ];

        $options = array_merge($options, $optionForToken);

        $response = $this->client->request($method, $uri, $options);

        if (!$this->needToRevalidate($response)) {
            return $response;
        }

        $optionForToken = [
            'headers' => [
                'Authorization' => $this->autenticate(true),
            ],
        ];

        $options = array_merge($options, $optionForToken);

        return $this->client->request($method, $uri, $options);

    }

    /**
     * @param bool|false $skipCache
     *
     * @return string
     * @throws AuthException
     */
    public function autenticate($skipCache = false)
    {

        if ($this->cacheEnabled && !$skipCache) {
            $cacheKey = self::CACHE_NAMESPACE.sha1($this->username.$this->password);
            if ($this->cache->contains($cacheKey)) {
                return $this->cache->fetch($cacheKey);
            }
        }

        $payload = [
            'auth' => [
                'username' => $this->username,
                'password' => $this->password,
            ],
        ];

        $response = $this->client->request('POST', self::BASE_URL, ['body' => json_encode($payload)]);

        $content = $response->getBody()->getContents();

        $contentArray = json_decode($content, true);

        if (!isset($contentArray["response"]["token"])) {
            throw new AuthException($content);
        }

        $token = $contentArray["response"]["token"];

        if ($this->cacheEnabled) {
            $this->cache->save($cacheKey, $token, self::TOKEN_EXPIRATION);
        }

        return $token;
    }

    /**
     * @inheritDoc
     */
    public function send(RequestInterface $request, array $options = [])
    {
        return $this->client->send($request, $options);
    }

    /**
     * @inheritDoc
     */
    public function sendAsync(RequestInterface $request, array $options = [])
    {
        return $this->client->sendAsync($request, $options);
    }

    /**
     * @inheritDoc
     */
    public function requestAsync($method, $uri, array $options = [])
    {
        return $this->client->requestAsync($method, $uri, $options);
    }

    /**
     * @inheritDoc
     */
    public function getConfig($option = null)
    {
        return $this->client->getConfig($option);
    }


    /**
     * @param Response $response
     *
     * @return bool
     */
    protected function needToRevalidate(Response $response)
    {

        $content = json_decode($response->getBody()->getContents(), true);

        $response->getBody()->rewind();

        return isset($content['response']['error_id']) && $content['response']['error_id'] == 'NOAUTH';

    }

}
