<?php


namespace BigBIT\Oddin\Utils;


use Composer\Autoload\ClassLoader;

/**
 * Class ClassMapResolver
 * @package BigBIT\Oddin\Utils
 */
class ClassMapResolver
{

    /** @var ClassLoader */
    private ?ClassLoader $classLoader = null;

    /** @var array */
    private array $classMap;

    /** @var string */
    private string $autoloadPath;

    /**
     * ClassMapResolver constructor.
     * @param string $autoloadPath
     */
    public function __construct(string $autoloadPath)
    {
        $this->classMap = [];
        $this->autoloadPath = $autoloadPath;
    }

    /**
     * @param string $className
     * @return string
     * @throws \Exception
     */
    public function getClassPath(string $className): string
    {
        if (!isset($this->classMap[$className])) {
            $loader = $this->getClassLoader();
            $this->classMap[$className] = $loader->findFile($className);
        }

        return $this->classMap[$className];
    }

    /**
     * @return ClassLoader
     */
    public function getClassLoader(): ClassLoader
    {
        if ($this->classLoader === null) {
            $this->classLoader = require($this->autoloadPath);
        }

        return $this->classLoader;
    }

}
