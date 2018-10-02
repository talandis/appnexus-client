<?php

namespace Audiens\AppnexusClient\entity;

class SegmentBilling
{
    use HydratableTrait;

    /**
     * @var int|null
     * The ID of the sharing record. Can be null in creation phase
     * Required on: PUT (in JSON) DELETE (in query string)
     */
    protected $id;

    /**
     * @var bool
     * The status of the mapping record. If set to true, mapping record is active.
     * Required on: POST
     */
    protected $active = true;

    /**
     * @var int|null
     * Read-only. Your member ID.
     */
    protected $member_id;

    /**
     * @var int
     * The AppNexus segment ID that is being mapped.
     * POST/PUT
     */
    protected $segment_id;

    /**
     * @var  int
     * The data provider ID assigned to you by the Data Marketplace. Note: The POST/CALL call will fail if you
     * submit an ID that is not owned by your account
     * Required on: POST/PUT
     */
    protected $data_provider_id;

    /**
     * @var int
     * The pricing category ID created on AppNexus. Note: The POST/PUT calls will fail if you submit an ID that
     * is not owned by your account
     * Required on : POST/PUT
     */
    protected $data_category_id;

    /**
     * @var bool
     * The setting to mark the segment as public or private. If set to true, then the segment will be shared to
     * all Data Marketplace buyers immediately.
     */
    protected $is_public = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active)
    {
        $this->active = $active;
    }

    public function getMemberId(): ?int
    {
        return $this->member_id;
    }

    public function getSegmentId(): int
    {
        return $this->segment_id;
    }

    public function setSegmentId(int $segment_id)
    {
        $this->segment_id = $segment_id;
    }

    public function getDataProviderId(): int
    {
        return $this->data_provider_id;
    }

    public function setDataProviderId(int $data_provider_id)
    {
        $this->data_provider_id = $data_provider_id;
    }

    public function getDataCategoryId(): int
    {
        return $this->data_category_id;
    }

    public function setDataCategoryId(int $data_category_id)
    {
        $this->data_category_id = $data_category_id;
    }

    public function getIsPublic(): bool
    {
        return $this->is_public;
    }

    public function setIsPublic(bool $is_public)
    {
        $this->is_public = $is_public;
    }
}
