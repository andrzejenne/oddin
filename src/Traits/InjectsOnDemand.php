<?php


namespace BigBIT\Oddin\Traits;


use BigBIT\Oddin\Singletons\DIResolver;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Trait InjectsOnDemand
 * @package BigBIT\Oddin\Traits
 */
trait InjectsOnDemand
{
    /**
     * @param string $propertyName
     * @return mixed|null
     * @throws InvalidArgumentException
     * @throws \ReflectionException
     */
    public function __get(string $propertyName) {
        $this->$propertyName = DIResolver::getInjectableFor($propertyName, $this);

        return $this->$propertyName;
    }

    /**
     * @param $propertyName
     * @param $value
     * @return mixed|null
     * @throws InvalidArgumentException
     * @throws \ReflectionException
     */
    public function __set($propertyName, $value)
    {
        $this->$propertyName = DIResolver::getInjectableFor($propertyName, $this);

        return $this->$propertyName;
    }

    /**
     * @param $propertyName
     * @return mixed|null
     * @throws InvalidArgumentException
     * @throws \ReflectionException
     */
    public function __isset($propertyName)
    {
        $this->$propertyName = DIResolver::getInjectableFor($propertyName, $this);

        return $this->$propertyName;
    }


}
