<?php

namespace Audiens\AppnexusClient\entity;

/**
 * Class UserSegment
 */
class UserSegment
{

    const UUID_SEPARATOR     = ',';
    const SEGBLOCK_SEPARATOR = ';';

//UUID,SEG_ID;SEG_CODE;EXPIRATION;TIMESTAMP;VALUE;MEMBER_ID
//5727816213491965430,78610639, "it.gender.male";7776000;1458191702;0;0
//5727816213491965430,77610639, "it.age.18-24";7776000;1458191702;0;0
//5727816213491965430,74610639, "it.residence.milan";7776000;1458191702;0;0
//6427816213491965430,72610639, "it.gender.female";7776000;1458191702;0;0
//6427816213491965430,71610639, "it.age.25-34";7776000;1458191702;0;0
//6427816213491965430,70610639, "it.residence.venice"; 7776000;1458191702;0;0
//
    protected $dmpId;

    protected $segmentId;

    protected $segmentCode;

    protected $timestamp;

    protected $value;

    protected $memberId;

    /**
     * @return mixed
     */
    public function getDmpId()
    {
        return $this->dmpId;
    }

    /**
     * @param mixed $dmpId
     */
    public function setDmpId($dmpId)
    {
        $this->dmpId = $dmpId;
    }

    /**
     * @return mixed
     */
    public function getSegmentId()
    {
        return $this->segmentId;
    }

    /**
     * @param mixed $segmentId
     */
    public function setSegmentId($segmentId)
    {
        $this->segmentId = $segmentId;
    }

    /**
     * @return mixed
     */
    public function getSegmentCode()
    {
        return $this->segmentCode;
    }

    /**
     * @param mixed $segmentCode
     */
    public function setSegmentCode($segmentCode)
    {
        $this->segmentCode = $segmentCode;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getMemberId()
    {
        return $this->memberId;
    }

    /**
     * @param mixed $memberId
     */
    public function setMemberId($memberId)
    {
        $this->memberId = $memberId;
    }

    /**
     * @return string
     */
    public function __toString()
    {

        $segBlock = implode(
            ';',
            [
                $this->segmentId,
                $this->segmentCode,
                $this->timestamp,
                $this->value,
                $this->memberId,
            ]
        );

        return implode(',', [$this->dmpId, $segBlock]);


    }


}
