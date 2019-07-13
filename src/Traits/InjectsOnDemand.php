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
     * @param $propertyName
     * @return mixed|null
     * @throws InvalidArgumentException
     * @throws \ReflectionException
     */
    public function __get($propertyName) {
        $this->$propertyName = DIResolver::getInjectableFor($propertyName, $this);

        return $this->$propertyName;
    }
}
