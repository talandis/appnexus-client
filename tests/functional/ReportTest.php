<?php

namespace Test\functional;

use Audiens\AppnexusClient\service\Report;
use Prophecy\Argument;
use Test\FunctionalTestCase;

/**
 * Class ReportTest
 */
class ReportTest extends FunctionalTestCase
{

    /**
     * @test
     */
    public function get_ticket_will_return_an_object()
    {
        $reportService = new Report($this->getAuth());
        $reportTicket = $reportService->getReportTicket();
        $this->assertNotEmpty($reportTicket->getReportId());
    }

    /**
     * @test
     */
    public function get_job_status_will_return_an_object()
    {
        $reportService = new Report($this->getAuth());
        $reportTicket = $reportService->getReportTicket();
        $reportStatus = $reportService->getReportStatus($reportTicket);

        $this->assertNotEmpty($reportStatus->getReportId());
        $this->assertNotEmpty($reportStatus->getStatus());
    }

    /**
     * @test
     */
    public function get_report_will_return_an_array()
    {
        $reportService = new Report($this->getAuth());
        $reportTicket = $reportService->getReportTicket();
        $reportStatus = $reportService->getReportStatus($reportTicket);

        $reportService->disableCache();
        $report = $reportService->getReport($reportStatus);

        $this->assertNotEmpty($report);

        $expectedHeaders = Report::REVENUE_REPORT;
        $expectedHeaders = $expectedHeaders['report']['columns'];

        $this->assertEquals($expectedHeaders, $report[0]);
    }




}
