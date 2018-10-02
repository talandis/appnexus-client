<?php

namespace Audiens\AppnexusClient\entity;

class ReportStatus extends ReportTicket
{

    use HydratableTrait;

    public const STATUS_READY = 'ready';

    /** @var  string */
    protected $status;

    /** @var  string */
    protected $name;

    /** @var string */
    protected $url;

    public function __construct()
    {
        $this->report_id = null;
        $this->cached    = false;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return bool
     */
    public function isReady()
    {
        return $this->status == self::STATUS_READY;
    }
}
