<?php


use BigBIT\DIBootstrap\Bootstrap;
use BigBIT\DIBootstrap\Exceptions\ClassNotFoundException;
use BigBIT\DIBootstrap\Exceptions\InvalidContainerImplementationException;
use BigBIT\DIBootstrap\Exceptions\PathNotFoundException;
use BigBIT\Oddin\Examples\child\DeprecatedChildClass;
use BigBIT\Oddin\Examples\ExampleContainer;
use BigBIT\Oddin\Examples\ExampleService;
use BigBIT\Oddin\Examples\parent\DeprecatedParentClass;
use BigBIT\Oddin\Utils\CacheResolver;
use BigBIT\Oddin\Utils\ClassMapResolver;
use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;

/**
 * Class DeprecatedTestCase
 */
class DeprecatedTestCase extends \PHPUnit\Framework\TestCase
{
    /** @var ContainerInterface */
    protected ContainerInterface $container;

    /**
     * @throws ClassNotFoundException
     * @throws InvalidContainerImplementationException
     * @throws PathNotFoundException
     */
    protected function setUp(): void
    {
        Bootstrap::useContainerImplementation(ExampleContainer::class);
        Bootstrap::detectVendorPath();
        $this->container = Bootstrap::getContainer();
        $this->container[ExampleService::class] = function() {
            return new ExampleService();
        };

        $this->container[\BigBIT\Oddin\Examples\ns\ExampleService::class] = function() {
            return new \BigBIT\Oddin\Examples\ns\ExampleService();
        };

        $this->container[\BigBIT\Oddin\Examples\parent\ExampleService::class] = function() {
            return new \BigBIT\Oddin\Examples\parent\ExampleService();
        };

        $this->container[DeprecatedParentClass::class] = function() {
            return new DeprecatedParentClass();
        };

        $this->container[DeprecatedChildClass::class] = function() {
            return new DeprecatedChildClass();
        };

        $this->container[ClassMapResolver::class] = function() {
            return new ClassMapResolver(
                dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php'
            );
        };

        $cacheResolver = new CacheResolver(
            $this->container->get(ClassMapResolver::class),
            new Psr16Cache(new ArrayAdapter())
        );

        $this->container[CacheResolver::class] = $cacheResolver->allowDeprecated();
    }
}
