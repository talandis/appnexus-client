<?php

namespace Test;

use Audiens\AppnexusClient\Auth;
use Doctrine\Common\Cache\CacheProvider;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use Prophecy\Argument;

/**
 * Class AuthTest
 */
class AuthTest extends TestCase
{

    /**
     * @test
     */
    public function should_append_the_authorization_token_when_performing_any_request()
    {

        $username = 'sample_username';
        $password = 'sample_password';
        $token = 'a_sample_token123456789';

        $tokenReponse = $this->getTokenResponse($token);

        $dummyStream = $this->prophesize(Stream::class);
        $dummyStream->getContents('');

        $dummyResponse = $this->prophesize(Response::class);
        $dummyResponse->getBody()->willReturn($dummyStream->reveal());

        $expectedRequestOptions = [
            'headers' => [
                'Authorization' => $token,
            ],
        ];

        $client = $this->getMock(ClientInterface::class);

        $client
            ->expects($this->at(0))
            ->method('request')
            ->with('POST', Auth::BASE_URL, $this->anything())
            ->willReturn($tokenReponse);

        $client
            ->expects($this->at(1))
            ->method('request')
            ->with('POST', 'random_url', $expectedRequestOptions)
            ->willReturn($dummyResponse->reveal());

        $cacheProvider = $this->prophesize(CacheProvider::class);

        $auth = new Auth($username, $password, $client, $cacheProvider->reveal());

        $auth->request('POST', 'random_url', []);

        $dummyStream->rewind()->shouldHaveBeenCalled(); // asserting that the stream will be rewinded

    }

    /**
     * @param $token
     *
     * @return Response
     */
    protected function getTokenResponse($token)
    {

        $responseBody = json_encode(
            [
                'response' => [
                    'token' => $token,
                ],
            ]
        );

        return new Response(200, [], $responseBody);

    }

}
