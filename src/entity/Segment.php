<?php

namespace Audiens\AppnexusClient\entity;

class Segment
{

    use HydratableTrait;

    /**
     * @var int|null
     * AppNexus ID assigned by the API to reference this segment.
     * Required: yes (on update only)
     */
    protected $id;

    /**
     * @var bool
     * Boolean value - determines whether the segment can be used.
     * required: no, default is active
     */
    protected $active = true;

    /**
     * @var int|null
     * The member ID that owns this segment.
     * Required: yes
     */
    protected $member_id;

    /**
     * @var string|null
     * A name used to describe the segment. This will be passed on the bid requests.
     * Required: no
     */
    protected $short_name;

    /**
     * @var  int|null
     * The number of minutes the user is kept in the segment. If you want to keep the user in
     * the segment for retargeting purposes, set to the desired number of minutes (or null for system maximum value 180 days).
     * If you want to add the user to the segment only for the duration of the ad call, set to 0. Changing this value does not
     * retroactively affect users already in the segment. Also, if a user is re-added, the expiration window resets.
     * Required: no
     */
    protected $expire_minutes = 2147483647;

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

    public function setMemberId(int $memberId)
    {
        $this->member_id = $memberId;
    }

    public function getName(): ?string
    {
        return $this->short_name;
    }

    public function setName(string $name = null)
    {
        $this->short_name = $name;
    }

    public function getExpireMinutes(): ?int
    {
        return $this->expire_minutes;
    }

    public function setExpireMinutes(int $expireMinutes = null)
    {
        $this->expire_minutes = $expireMinutes;
    }
}
