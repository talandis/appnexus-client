<?php

namespace Audiens\AppnexusClient\entity;

/**
 * Class Category
 */
class Category
{

    use HydratableTrait;

    /** @var  string */
    protected $id;

    /** @var  string */
    protected $name;

    /** @var   string */
    protected $description;

    /** @var  bool */
    protected $is_system;

    /** @var  string */
    protected $timestamp;

    /** @var  string */
    protected $parent_category;

    /**
     * Category constructor
     */
    public function __construct()
    {
    }

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
     * @return boolean
     */
    public function isIsSystem()
    {
        return $this->is_system;
    }

    /**
     * @param boolean $is_system
     */
    public function setIsSystem($is_system)
    {
        $this->is_system = $is_system;
    }

    /**
     * @return string
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param string $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return string
     */
    public function getParentCategory()
    {
        return $this->parent_category;
    }

    /**
     * @param string $parent_category
     */
    public function setParentCategory($parent_category)
    {
        $this->parent_category = $parent_category;
    }
}
