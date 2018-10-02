<?php

namespace Audiens\AppnexusClient\entity;

class MemberDataSharing
{
    public const SEGMENT_EXPOSURE_ALL  = 'all';
    public const SEGMENT_EXPOSURE_LIST = 'list';

    use HydratableTrait;

    /**
     * @var int
     * The ID of the sharing record.
     * Required on: PUT/DELETE, in query string
     */
    protected $id;

    /**
     * @var int
     * Read-only. Your member ID.
     */
    protected $data_member_id;

    /**
     * @var int
     * The ID of the member with whom you are sharing segments.
     * Required on: POST
     */
    protected $buyer_member_id;

    /**
     * @var string
     * Whether you share all of your segments or a list of specific segments with the member.
     * Possible values: "all" or "list".  If you choose "all", any newly created segments will automatically
     * be shared with the buyer member. If you create custom segments that should only be accessible to certain buyers,
     * you should use "list" exposure.
     * see SEGMENT_EXPOSURE_*
     * Required on: POST
     */
    protected $segment_exposure;

    /**
     * @var MemberDataSharingSegment[]
     * If segment_exposure is "list", the list of segments that you are sharing with the member.
     */
    protected $segments;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getDataMemberId(): int
    {
        return $this->data_member_id;
    }

    public function setDataMemberId(int $data_member_id)
    {
        $this->data_member_id = $data_member_id;
    }

    public function getBuyerMemberId(): int
    {
        return $this->buyer_member_id;
    }

    public function setBuyerMemberId(int $buyer_member_id)
    {
        $this->buyer_member_id = $buyer_member_id;
    }

    public function getSegmentExposure(): string
    {
        return $this->segment_exposure;
    }

    public function setSegmentExposure(string $segment_exposure)
    {
        $this->segment_exposure = $segment_exposure;
    }

    public function getSegments(): array
    {
        return $this->segments ?? [];
    }

    public function setSegments(array $segments)
    {
        $this->segments = $segments;
    }

    public function addSegments(MemberDataSharingSegment $segment)
    {
        $this->segments[] = $segment;
    }
}
