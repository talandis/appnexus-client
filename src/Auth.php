<?php

namespace Audiens\AppnexusClient;

use Audiens\AppnexusClient\authentication\AuthStrategyInterface;
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

    /** @var string */
    protected $token;

    /** @var string */
    protected $username;

    /** @var string */
    protected $password;

    protected $authStrategy;

    /**
     * @param                       $username
     * @param                       $password
     * @param ClientInterface       $clientInterface
     * @param AuthStrategyInterface $authStrategy
     */
    public function __construct(
        $username,
        $password,
        ClientInterface $clientInterface,
        AuthStrategyInterface $authStrategy
    ) {
        $this->username = $username;
        $this->password = $password;

        $this->client = $clientInterface;
        $this->authStrategy = $authStrategy;

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
                'Authorization' => $this->authStrategy->authenticate($this->username, $this->password),
            ],
        ];

        $options = array_merge($options, $optionForToken);

        $response = $this->client->request($method, $uri, $options);

        if (!$this->needToRevalidate($response)) {
            return $response;
        }

        $optionForToken = [
            'headers' => [
                'Authorization' => $this->authStrategy->authenticate($this->username, $this->password),
            ],
        ];

        $options = array_merge($options, $optionForToken);

        return $this->client->request($method, $uri, $options);

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
