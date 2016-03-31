<?php

namespace Test;

use Dotenv\Dotenv;
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

}
