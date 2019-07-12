<?php


namespace BigBIT\Oddin\Utils;


use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class CacheResolver
 * @package BigBIT\Oddin\Utils
 */
class CacheResolver
{
    /** @var CacheInterface */
    private $cache;

    /** @var PropertyAnnotationReader|null */
    private $reader;

    /**
     * CacheResolver constructor.
     * @param CacheInterface $cache
     */
    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param string $className
     * @return bool
     * @throws InvalidArgumentException
     */
    public function hasInjectables(string $className): bool {
        return $this->cache->has($className);
    }

    /**
     * @param string $className
     * @return mixed
     * @throws InvalidArgumentException
     * @throws \ReflectionException
     */
    public function getInjectables(string $className) {
        if (!$this->hasInjectables($className)) {
            $injectables = $this->resolveInjectables($className);
        } else {
            $injectables = $this->cache->get($className);
        }

        return $injectables;
    }

    /**
     * @param string $className
     * @return array
     * @throws \ReflectionException
     */
    private function resolveInjectables($className): array {
        $reflection = new \ReflectionClass($className);

        $reader = $this->getAnnotationReader();

        $properties = $reader->getProperties($reflection);

        return $properties;
    }

    /**
     * @return PropertyAnnotationReader
     */
    private function getAnnotationReader() {
        if (!$this->reader) {
            $this->reader = new PropertyAnnotationReader();
        }

        return $this->reader;
    }
}
