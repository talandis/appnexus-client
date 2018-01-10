<?php

namespace Audiens\AppnexusClient\entity;

/**
 * Class MemberDataSharing
 */
class MemberDataSharing
{
    const SEGMENT_EXPOSURE_ALL = 'all';
    const SEGMENT_EXPOSURE_LIST = 'list';

    use HydratableTrait;

    /** @var  string */
    protected $id;

    /** @var  string */
    protected $data_member_id;

    /** @var string */
    protected $buyer_member_id;

    /**
     * @var  string
     * see SEGMENT_EXPOSURE_*
     */
    protected $segment_exposure;

    /** @var  MemberDataSharingSegment[] */
    protected $segments;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getDataMemberId()
    {
        return $this->data_member_id;
    }

    /**
     * @param string $data_member_id
     */
    public function setDataMemberId($data_member_id)
    {
        $this->data_member_id = $data_member_id;
    }

    /**
     * @return string
     */
    public function getBuyerMemberId()
    {
        return $this->buyer_member_id;
    }

    /**
     * @param string $buyer_member_id
     */
    public function setBuyerMemberId($buyer_member_id)
    {
        $this->buyer_member_id = $buyer_member_id;
    }

    /**
     * @return string
     */
    public function getSegmentExposure()
    {
        return $this->segment_exposure;
    }

    /**
     * @param string $segment_exposure
     */
    public function setSegmentExposure($segment_exposure)
    {
        $this->segment_exposure = $segment_exposure;
    }

    /**
     * @return MemberDataSharingSegment[]
     */
    public function getSegments()
    {
        return $this->segments;
    }

    /**
     *
     */
    public function setSegments($segments)
    {
        $this->segments = $segments;
    }

    /**
     * @param MemberDataSharingSegment $segment
     */
    public function addSegments($segment)
    {
        $this->segments[] = $segment;
    }
}
