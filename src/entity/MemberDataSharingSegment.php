<?php

namespace Audiens\AppnexusClient\entity;

/**
 * Class MemberDataSharingSegment
 */
class MemberDataSharingSegment
{
    use HydratableTrait;

    /** @var string */
    protected $id;

    /** @var string */
    protected $name;

    /**
     * @return mixed
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
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
