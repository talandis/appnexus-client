<?php

namespace Audiens\AppnexusClient\entity;

/**
 * Class ReportTicket
 */
class ReportTicket
{

    use HydratableTrait;

    /** @var  int */
    protected $report_id;

    /** @var  string */
    protected $cached;

    /**
     * @return int
     */
    public function getReportId()
    {
        return $this->report_id;
    }

    /**
     * @param int $report_id
     */
    public function setReportId($report_id)
    {
        $this->report_id = $report_id;
    }

    /**
     * @return string
     */
    public function getCached()
    {
        return $this->cached;
    }

    /**
     * @param string $cached
     */
    public function setCached($cached)
    {
        $this->cached = $cached;
    }
}
