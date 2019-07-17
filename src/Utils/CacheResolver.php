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

    /** @var SimpleClassReader */
    private $reader;

    /** @var ClassMapResolver */
    private $classMapResolver;

    /** @var array */
    private $classMap;

    /**
     * CacheResolver constructor.
     * @param CacheInterface $cache
     * @param ClassMapResolver $classMapResolver
     */
    public function __construct(CacheInterface $cache, ClassMapResolver $classMapResolver)
    {
        $this->cache = $cache;
        $this->classMapResolver = $classMapResolver;
    }

    /**
     * @param string $className
     * @return bool
     */
    public function hasInjectables(string $className): bool {
        $classMap = $this->getClassMap();

        return isset($classMap[$className]);
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
            $injectables = $this->classMap[$className];
        }

        return $injectables;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function shutDown() {
        $this->cache->set('classMap', $this->classMap);
    }

    /**
     * @param string $className
     * @return array
     * @throws \ReflectionException
     */
    private function resolveInjectables(string $className): array {
        $reflection = new \ReflectionClass($className);

        $reader = $this->getAnnotationReader();

        $properties = $reader->getProperties($reflection);

        $this->classMap[$className] = $properties;

        return $properties;
    }

    /**
     * @return SimpleClassReader
     */
    private function getAnnotationReader() {
        if ($this->reader === null) {
            $this->reader = new SimpleClassReader($this->classMapResolver);
        }

        return $this->reader;
    }

    /**
     * @return array|mixed
     * @throws InvalidArgumentException
     */
    private function getClassMap() {
        if ($this->cache->has('classMap')) {
            $this->classMap = $this->cache->get('classMap');
        }
        else {
            $this->classMap = [];
        }

        return $this->classMap;
    }
}
