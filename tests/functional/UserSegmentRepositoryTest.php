<?php

namespace Test\functional;

use Audiens\AppnexusClient\Auth;
use Audiens\AppnexusClient\authentication\AdnxStrategy;
use Audiens\AppnexusClient\authentication\AppnexusStrategy;
use Audiens\AppnexusClient\entity\Segment;
use Audiens\AppnexusClient\repository\SegmentRepository;
use Audiens\AppnexusClient\repository\UserSegmentRepository;
use Doctrine\Common\Cache\FilesystemCache;
use GuzzleHttp\Client;
use Prophecy\Argument;
use Test\FunctionalTestCase;
use Test\TestCase;

/**
 * Class UserSegmentRepositoryTest
 */
class UserSegmentRepositoryTest extends FunctionalTestCase
{

    /**
     * @test
     */
    public function test()
    {

        $this->markTestIncomplete('to do');

        $repository = new UserSegmentRepository($this->getAuth());

        $fileAsString = "5727816213491965430,78610639, 'it.gender.male';7776000;1458191702;0;0\n";

        $repositoryResponse = $repository->upload($fileAsString, getenv('MEMBER_ID'));

    }

    /**
     * @param bool|true $cacheToken
     *
     * @return Auth
     */
    protected function getAuth($cacheToken = true)
    {

        $cache = $cacheToken ? new FilesystemCache('build') : null;
        $client = new Client();

        $authStrategy = new AppnexusStrategy(new Client(), $cache);

        $authClient = new Auth(getenv('USERNAME'), getenv('PASSWORD'), $client, $authStrategy);

        return $authClient;

    }

}
