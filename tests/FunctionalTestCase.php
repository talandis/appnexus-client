<?php

namespace Test;

use Audiens\AppnexusClient\Auth;
use Audiens\AppnexusClient\authentication\SandboxStrategy;
use Audiens\AppnexusClient\facade\AppnexusFacade;
use Audiens\AppnexusClient\repository\SegmentRepository;
use Audiens\AppnexusClient\service\Report;
use Audiens\AppnexusClient\service\UserUpload;
use Doctrine\Common\Cache\FilesystemCache;
use Dotenv\Dotenv;
use GuzzleHttp\Client;
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

        $authStrategy = new SandboxStrategy(new Client(), $cache);

        $authClient = new Auth(getenv('USERNAME'), getenv('PASSWORD'), $client, $authStrategy);

        return $authClient;
    }

    /**
     * @param bool|true $cacheToken
     *
     * @return SegmentRepository
     */
    protected function getSegmentRepository($cacheToken = true)
    {

        $authClient = $this->getAuth($cacheToken);

        $segmentRepository = new SegmentRepository($authClient);
        $segmentRepository->setBaseUrl(SegmentRepository::SANDBOX_BASE_URL);

        return $segmentRepository;
    }


    /**
     * @param bool|true $cacheToken
     *
     * @return UserUpload
     */
    protected function getUserUpload($cacheToken = true)
    {
        $authClient = $this->getAuth($cacheToken);

        $userUpload = new UserUpload($authClient);
        $userUpload->setBaseUrl(UserUpload::SANDBOX_BASE_URL);

        return $userUpload;
    }

    /**
     * @param bool|true $cacheToken
     *
     * @return Report
     */
    protected function getReport($cacheToken = true)
    {
        $authClient = $this->getAuth($cacheToken);

        $userUpload = new Report($authClient);
        $userUpload->setBaseUrl(Report::SANDBOX_BASE_URL);
        $userUpload->setBaseUrlDownload(Report::SANDBOX_BASE_URL_DOWNLOAD);

        return $userUpload;
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
