<?php


namespace BBIT\Oddin\Traits;


use BBIT\Oddin\Singletons\DIResolver;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Trait InjectsOnDemand
 * @package BBIT\Oddin\Traits
 */
trait InjectsOnDemand
{
    /**
     * @param $serviceName
     * @return mixed|null
     * @throws InvalidArgumentException
     */
    public function __get($serviceName) {
        $this->$serviceName = DIResolver::for($this)->get($serviceName);

        return $this->$serviceName;
    }
}
