<?php


namespace BigBIT\Oddin\Utils;


use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;

/**
 * Class CacheResolver
 * @package BigBIT\Oddin\Utils
 */
class CacheResolver
{
    /** @var CacheInterface */
    private CacheInterface $cache;

    /** @var SimpleClassReader */
    private ?SimpleClassReader $reader = null;

    /** @var ClassMapResolver */
    private ClassMapResolver $classMapResolver;

    /** @var array */
    private array $classMap = [];

    /**
     * CacheResolver constructor.
     * @param ClassMapResolver $classMapResolver
     * @param CacheInterface $cache
     */
    public function __construct(ClassMapResolver $classMapResolver, CacheInterface $cache = null)
    {
        if ($cache) {
            $this->setCache($cache);
        }

        $this->classMapResolver = $classMapResolver;
    }

    /**
     * @param string $className
     * @return bool
     * @throws InvalidArgumentException
     */
    public function hasInjectables(string $className): bool
    {
        $classMap = $this->getClassMap();

        return isset($classMap[$className]);
    }

    /**
     * @param CacheInterface $cache
     */
    public function setCache(CacheInterface $cache): void
    {
        $this->cache = $cache;
    }

    /**
     * @param string $className
     * @return mixed
     * @throws InvalidArgumentException
     * @throws \ReflectionException
     */
    public function getInjectables(string $className)
    {
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
    public function shutDown(): void
    {
        $this->getCache()->set('classMap', $this->classMap);
    }

    /**
     * @param string $className
     * @return array
     * @throws \ReflectionException
     * @throws \Exception
     */
    private function resolveInjectables(string $className): array
    {
        $reflection = new \ReflectionClass($className);

        $reader = $this->getClassReader();

        $properties = $reader->getProperties($reflection);

        $this->classMap[$className] = $properties;

        return $properties;
    }

    /**
     * @return SimpleClassReader
     */
    private function getClassReader()
    {
        if ($this->reader === null) {
            $this->reader = new SimpleClassReader($this->classMapResolver);
        }

        return $this->reader;
    }

    /**
     * @return array|mixed
     * @throws InvalidArgumentException
     */
    private function getClassMap()
    {
        if (!$this->classMap) {
            if ($this->getCache()->has('classMap')) {
                $this->classMap = $this->cache->get('classMap');
            } else {
                $this->classMap = [];
            }
        }

        return $this->classMap;
    }

    /**
     * @return CacheInterface|Psr16Cache
     */
    private function getCache(): CacheInterface {
        if (null === $this->cache) {
            $this->cache = new Psr16Cache(new ArrayAdapter());
        }

        return $this->cache;
    }
}
