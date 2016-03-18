<?php

namespace Test;

use Dotenv\Dotenv;
use Prophecy\Argument;

/**
 * Class SegmentRepositoryFunctionTest
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $dotenv = new Dotenv(__DIR__.'/../');
        $dotenv->load();

        parent::setUp();
    }

}
