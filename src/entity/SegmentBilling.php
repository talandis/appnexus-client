<?php

namespace Audiens\AppnexusClient\entity;

/**
 * Class SegmentBilling
 */
class SegmentBilling
{
    use HydratableTrait;

    /** @var  int */
    protected $id;

    /** @var  bool */
    protected $active = true;

    /** @var  int */
    protected $member_id;

    /** @var  int */
    protected $segment_id;

    /** @var  int */
    protected $data_provider_id;

    /** @var  int */
    protected $data_category_id;

    /** @var  bool */
    protected $is_public;

    /**
     * SegmentBilling constructor.
     */
    public function __construct()
    {
        $this->is_public = false;
    }

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
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
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
     * @return int
     */
    public function getSegmentId()
    {
        return $this->segment_id;
    }

    /**
     * @param int $segment_id
     */
    public function setSegmentId($segment_id)
    {
        $this->segment_id = $segment_id;
    }

    /**
     * @return int
     */
    public function getDataProviderId()
    {
        return $this->data_provider_id;
    }

    /**
     * @param int $data_provider_id
     */
    public function setDataProviderId($data_provider_id)
    {
        $this->data_provider_id = $data_provider_id;
    }

    /**
     * @return int
     */
    public function getDataCategoryId()
    {
        return $this->data_category_id;
    }

    /**
     * @param int $data_category_id
     */
    public function setDataCategoryId($data_category_id)
    {
        $this->data_category_id = $data_category_id;
    }

    /**
     * @return bool
     */
    public function getIsPublic()
    {
        return $this->is_public;
    }

    /**
     * @param bool $is_public
     */
    public function setIsPublic($is_public)
    {
        $this->is_public = $is_public;
    }
}
