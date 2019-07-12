<?php


namespace BBIT\Oddin\Utils;

use Psr\Container\ContainerInterface;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class ServiceResolver
 * @package BBIT\Oddin\Utils
 * @todo - crate custom exceptions
 */
class ServiceResolver
{
    /** @var string[] */
    private $services;

    /** @var CacheResolver */
    private $cacheResolver;

    /** @var ContainerInterface */
    private $container;

    /** @var string */
    private $objectClass;

    /** @var mixed[] */
    private $instances = [];

    /**
     * ServiceResolver constructor.
     * @param string $objectClass
     * @param ContainerInterface $container
     */
    public function __construct(
        string $objectClass,
        ContainerInterface $container
    ) {
        $this->objectClass = $objectClass;
        $this->cacheResolver = $container->get(CacheResolver::class);
        $this->container = $container;
    }

    /**
     * @param string $propertyName
     * @return mixed
     * @throws InvalidArgumentException
     * @throws \Exception
     */
    public function get($propertyName)
    {
        $serviceName = $this->getServiceForProperty($propertyName);

        if (!isset($this->instances[$serviceName])) {
            if ($this->container->has($serviceName)) {
                $this->instances[$serviceName] = $this->container->get($serviceName);
            } else {
                throw new \Exception("Service not defined $serviceName");
            }
        }

        return $this->instances[$serviceName];
    }

    /**
     * @param string $propertyName
     * @return mixed
     * @throws InvalidArgumentException
     * @throws \Exception
     */
    private function getServiceForProperty($propertyName)
    {
        $services = $this->getServices();
        if (!isset($services[$propertyName])) {
            throw new \Exception('service for property ' . $propertyName . ' not defined');
        }

        return $services[$propertyName];
    }

    /**
     * @return mixed|string
     * @throws InvalidArgumentException
     * @throws \ReflectionException
     */
    private function getServices()
    {
        if (!$this->services) {
            $this->services = $this->cacheResolver->getInjectables($this->objectClass);
        }

        return $this->services;
    }
}
