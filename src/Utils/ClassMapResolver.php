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

    /** @var string */
    private $rootDir;

    /** @var array */
    private $classMap;

    /**
     * ClassMapResolver constructor.
     * @param string $rootDir
     */
    public function __construct(string $rootDir)
    {
        $this->rootDir = $rootDir;
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
        if (!$this->classLoader) {
            $loader = new ClassLoader();
            $composerDir = $this->rootDir . DIRECTORY_SEPARATOR . 'vendor'
                . DIRECTORY_SEPARATOR . 'composer'
                . DIRECTORY_SEPARATOR;

            $map = require $composerDir . 'autoload_namespaces.php';
            foreach ($map as $namespace => $path) {
                $loader->set($namespace, $path);
            }

            $map = require $composerDir . 'autoload_psr4.php';
            foreach ($map as $namespace => $path) {
                $loader->setPsr4($namespace, $path);
            }

            $classMap = require $composerDir . 'autoload_classmap.php';
            if ($classMap) {
                $loader->addClassMap($classMap);
            }

            $this->classLoader = $loader;
        }

        return $this->classLoader;
    }

}