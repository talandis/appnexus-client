<?php

namespace Audiens\AppnexusClient\entity;

class ReportTicket
{

    use HydratableTrait;

    /** @var  int|null */
    protected $report_id;

    /** @var  bool */
    protected $cached;

    /**
     * @return int|null
     */
    public function getReportId()
    {
        return $this->report_id;
    }

    /**
     * @param int|null $report_id
     */
    public function setReportId($report_id)
    {
        $this->report_id = $report_id;
    }

    /**
     * @return bool
     */
    public function getCached()
    {
        return $this->cached;
    }

    /**
     * @param bool $cached
     */
    public function setCached($cached)
    {
        $this->cached = $cached;
    }
}
