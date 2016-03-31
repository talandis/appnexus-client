<?php

namespace Audiens\AppnexusClient\entity;

use GeneratedHydrator\Configuration;
use Zend\Hydrator\ObjectProperty;

/**
 * Class HydratableTrait
 */
trait HydratableTrait
{
    /**
     * @param array $objectArray
     *
     * @return self
     */
    public static function fromArray(array $objectArray)
    {

        $object = new self();
        self::getHydrator()->hydrate($objectArray, $object);

        return $object;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return self::getHydrator()->extract($this);
    }

    /**
     * @return ObjectProperty
     */
    private static function getHydrator()
    {

        $config = new Configuration(self::class);
        $hydratorClass = $config->createFactory()->getHydratorClass();
        $hydrator = new $hydratorClass();

        return $hydrator;

    }
}
