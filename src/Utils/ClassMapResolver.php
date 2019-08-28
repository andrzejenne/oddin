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
    private $classLoader;

    /** @var array */
    private $classMap;

    /**
     * ClassMapResolver constructor.
     */
    public function __construct()
    {
        $this->classMap = [];
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
    public function getClassLoader()
    {
        if ($this->classLoader === null) {
            $this->classLoader = require('vendor/autoload.php');
        }

        return $this->classLoader;
    }

}
