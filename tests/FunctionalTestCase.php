<?php

namespace Test;

use Audiens\AppnexusClient\Auth;
use Audiens\AppnexusClient\authentication\AdnxStrategy;
use Audiens\AppnexusClient\facade\AppnexusFacade;
use Doctrine\Common\Cache\FilesystemCache;
use Dotenv\Dotenv;
use Guzzle\Http\Client;
use Prophecy\Argument;

/**
 * Class SegmentRepositoryFunctionTest
 */
class FunctionalTestCase extends \PHPUnit_Framework_TestCase
{

    const REQUIRED_ENV = [
        'USERNAME',
        'PASSWORD',
        'MEMBER_ID',
    ];

    protected function setUp()
    {

        if (!$this->checkEnv()) {
            $this->markTestSkipped('cannotInitialize enviroment tests will be skipped');
        }

        parent::setUp();
    }


    /**
     * @return bool
     */
    private function checkEnv()
    {

        try {
            $dotenv = new Dotenv(__DIR__.'/../');
            $dotenv->load();
        } catch (\Exception $e) {
        }

        $env = true;

        foreach (self::REQUIRED_ENV as $requiredEnv) {
            if (!getenv($requiredEnv)) {
                $env = false;
            }
        }

        return $env;
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

        $authStrategy = new AdnxStrategy(new Client(), $cache);

        $authClient = new Auth(getenv('USERNAME'), getenv('PASSWORD'), $client, $authStrategy);

        return $authClient;
    }


    /**
     * @return AppnexusFacade
     */
    protected function getFacade()
    {

        $facade = new AppnexusFacade(getenv('USERNAME'), getenv('PASSWORD'), getenv('MEMBER_ID'));

        return $facade;

    }
}
