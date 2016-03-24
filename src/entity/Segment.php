<?php

namespace Audiens\AppnexusClient\entity;

use GeneratedHydrator\Configuration;

/**
 * Class Segment
 */
class Segment
{

    /** @var  int */
    protected $id;

    /** @var  bool */
    protected $active = true;

    /** @var  string */
    protected $description;

    /** @var  int */
    protected $member_id;

    /** @var  string */
    protected $code;

    /** @var  string */
    protected $provider;

    /** @var  float */
    protected $price;

    /** @var  string */
    protected $short_name;

    /** @var  int */
    protected $expire_minutes;

    /** @var  string */
    protected $category;

    /** @var  \Datetime */
    protected $last_activity;

    /** @var  bool */
    protected $enable_rm_piggy_back;

    /**
     * Segment constructor
     */
    public function __construct()
    {
        $this->active = true;
        $this->last_activity = new \DateTime();
        $this->price = 0.0;
        $this->provider = 'base-provider';
        $this->expire_minutes = 2147483647;
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
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getMemberId()
    {
        return $this->member_id;
    }

    /**
     * @param int $memberId
     */
    public function setMemberId($memberId)
    {
        $this->member_id = $memberId;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param string $provider
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->short_name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->short_name = $name;
    }

    /**
     * @return int
     */
    public function getExpireMinutes()
    {
        return $this->expire_minutes;
    }

    /**
     * @param int $expireMinutes
     */
    public function setExpireMinutes($expireMinutes)
    {
        $this->expire_minutes = $expireMinutes;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return \Datetime
     */
    public function getLastActivity()
    {
        return $this->last_activity;
    }

    /**
     * @param \Datetime $lastActivity
     */
    public function setLastActivity(\Datetime $lastActivity)
    {
        $this->last_activity = $lastActivity;
    }

    /**
     * @return boolean
     */
    public function isEnableRmPiggyback()
    {
        return $this->enable_rm_piggy_back;
    }

    /**
     * @param boolean $enableRmPiggyback
     */
    public function setEnableRmPiggyback($enableRmPiggyback)
    {
        $this->enable_rm_piggy_back = $enableRmPiggyback;
    }

    /**
     * @param array $segmentArray
     *
     * @return Segment
     */
    public static function fromArray(array $segmentArray)
    {

        $config = new Configuration(Segment::class);
        $hydratorClass = $config->createFactory()->getHydratorClass();
        $hydrator = new $hydratorClass();
        $segment = new self();

        $hydrator->hydrate($segmentArray, $segment);

        return $segment;
    }

    /**
     * @return array
     */
    public function torray()
    {

        $config = new Configuration(Segment::class);
        $hydratorClass = $config->createFactory()->getHydratorClass();
        $hydrator = new $hydratorClass();

        return $hydrator->extract($this);

    }

}
