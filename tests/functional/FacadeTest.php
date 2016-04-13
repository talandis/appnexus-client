<?php

namespace Test\functional;

use Audiens\AppnexusClient\service\Report;
use Prophecy\Argument;
use Test\FunctionalTestCase;

/**
 * Class FacadeTest
 */
class FacadeTest extends FunctionalTestCase
{

    /**
     * @test
     */
    public function get_report_will_return_a_report()
    {

        $facade = $this->getFacade();

        $report = $facade->getReport();

        $expectedHeaders = Report::REVENUE_REPORT;
        $expectedHeaders = $expectedHeaders['report']['columns'];

        $this->assertEquals($expectedHeaders, $report[0]);
    }


}
