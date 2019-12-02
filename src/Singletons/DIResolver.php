<?php


namespace BigBIT\Oddin\Singletons;


use BigBIT\Oddin\Utils\CacheResolver;
use Psr\Container\ContainerInterface;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class DIResolver
 * @package BigBIT\Oddin\Singletons
 */
class DIResolver
{
    /** @var DIResolver */
    static DIResolver $instance;

    /** @var ContainerInterface */
    private ContainerInterface $container;

    /** @var string[] */
    private array $services;

    /** @var CacheResolver */
    private CacheResolver $cacheResolver;

    /**
     * Resolver constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        register_shutdown_function(function () {
            $this->shutDown();
        });

        $this->services = [];
    }

    /**
     * @param ContainerInterface $container
     * @return DIResolver
     */
    final public static function create(ContainerInterface $container): DIResolver
    {
        static::$instance = new static($container);

        return static::$instance;
    }

    /**
     * @param string $propertyName
     * @param mixed $object
     * @return mixed
     * @throws InvalidArgumentException
     * @throws \ReflectionException
     */
    final public static function getInjectableFor(string $propertyName, $object)
    {
        return static::$instance->resolve($propertyName, get_class($object));
    }

    /**
     * @param string $propertyName
     * @param string $className
     * @return mixed
     * @throws InvalidArgumentException
     * @throws \ReflectionException
     * @throws \Exception
     */
    private function resolve(string $propertyName, string $className)
    {
        $injectables = $this->getInjectablesFor($className);

        $injectable = &$injectables[$propertyName];
        if ($injectable) {
            return $this->container->get($injectable);
        }

        throw new \Exception("Injectable for $propertyName in $className not found");
    }

    /**
     * @param string $className
     * @return mixed|string
     * @throws InvalidArgumentException
     * @throws \ReflectionException
     */
    private function getInjectablesFor(string $className)
    {
        if (!isset($this->services[$className])) {
            $this->services[$className] = $this->getCacheResolver()->getInjectables($className);
        }

        return $this->services[$className];
    }

    /**
     * @return CacheResolver|mixed
     */
    private function getCacheResolver(): CacheResolver
    {
        if ($this->cacheResolver === null) {
            $this->cacheResolver = $this->container->get(CacheResolver::class);
        }

        return $this->cacheResolver;
    }

    /**
     * @throws InvalidArgumentException
     */
    private function shutDown()
    {
        if ($this->cacheResolver instanceof CacheResolver) {
            $this->cacheResolver->shutDown();
        }
    }
}
