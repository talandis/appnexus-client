<?php

namespace Test;

use Dotenv\Dotenv;
use Prophecy\Argument;

/**
 * Class SegmentRepositoryFunctionTest
 */
class FunctionalTestCase extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {

        $envOk = true;

        $requiredEnvs = [
            'USERNAME',
            'PASSWORD',
            'MEMBER_ID',
        ];

        foreach ($requiredEnvs as $requiredEnv) {
            if (!getenv($requiredEnv)) {
                $envOk = false;
            }
        }

        if (!$envOk) {
            try {
                $dotenv = new Dotenv(__DIR__.'/../');
                $dotenv->load();
            } catch (\Exception $e) {
                $envOk = false;
            }
        }

        if (!$envOk) {
            $this->markTestSkipped('cannotInitialize enviroment tests will be skipped');
        }


        parent::setUp();
    }

}
