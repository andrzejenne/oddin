<?php


namespace BigBIT\Oddin\Utils;


use Composer\Autoload\ClassLoader;
use Composer\Composer;
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

    /** @var ClassReader */
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
     * @throws InvalidArgumentException
     */
    public function hasInjectables(string $className): bool {
        return isset($this->classMap[$className]);
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
     * @param string $className
     * @return array
     * @throws \ReflectionException
     * @throws InvalidArgumentException
     * @throws \Exception
     */
    private function resolveInjectables(string $className): array {
        $reflection = new \ReflectionClass($className);

        $reader = $this->getAnnotationReader();

        $properties = $reader->getProperties($reflection);

        $this->classMap[$className] = $properties;

        $this->cache->set('classMap', $this->classMap);

        return $properties;
    }

    /**
     * @return ClassReader
     */
    private function getAnnotationReader() {
        if (!$this->reader) {
            $this->reader = new ClassReader($this->classMapResolver);
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
