<?php

namespace Audiens\AppnexusClient\entity;

/**
 * Class UploadJob
 */
class UploadTicket
{

    use HydratableTrait;

    /** @var  int */
    protected $id;

    /** @var  string */
    protected $job_id;

    /** @var  int */
    protected $member_id;

    /** @var  string */
    protected $last_modified;

    /** @var  string */
    protected $upload_url;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getJobId()
    {
        return $this->job_id;
    }

    /**
     * @param string $job_id
     */
    public function setJobId($job_id)
    {
        $this->job_id = $job_id;
    }

    /**
     * @return int
     */
    public function getMemberId()
    {
        return $this->member_id;
    }

    /**
     * @param int $member_id
     */
    public function setMemberId($member_id)
    {
        $this->member_id = $member_id;
    }

    /**
     * @return string
     */
    public function getLastModified()
    {
        return $this->last_modified;
    }

    /**
     * @param string $last_modified
     */
    public function setLastModified($last_modified)
    {
        $this->last_modified = $last_modified;
    }

    /**
     * @return string
     */
    public function getUploadUrl()
    {
        return $this->upload_url;
    }

    /**
     * @param string $upload_url
     */
    public function setUploadUrl($upload_url)
    {
        $this->upload_url = $upload_url;
    }
}
