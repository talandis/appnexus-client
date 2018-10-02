<?php

namespace Audiens\AppnexusClient\entity;

class MemberDataSharingSegment implements \JsonSerializable
{
    use HydratableTrait;

    /** @var int */
    protected $id;

    /** @var string|null */
    protected $name;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
        ];
    }
}
