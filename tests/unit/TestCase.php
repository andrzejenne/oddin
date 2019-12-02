<?php


use BigBIT\DIBootstrap\Bootstrap;
use BigBIT\DIBootstrap\Exceptions\ClassNotFoundException;
use BigBIT\DIBootstrap\Exceptions\InvalidContainerImplementationException;
use BigBIT\DIBootstrap\Exceptions\PathNotFoundException;
use BigBIT\Oddin\Examples\child\ChildClass;
use BigBIT\Oddin\Examples\ExampleContainer;
use BigBIT\Oddin\Examples\ExampleService;
use BigBIT\Oddin\Examples\parent\ParentClass;
use BigBIT\Oddin\Utils\CacheResolver;
use BigBIT\Oddin\Utils\ClassMapResolver;
use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;

class TestCase extends \PHPUnit\Framework\TestCase
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

        $this->container[ParentClass::class] = function() {
            return new ParentClass();
        };

        $this->container[ChildClass::class] = function() {
            return new ChildClass();
        };

        $this->container[ClassMapResolver::class] = function() {
            return new ClassMapResolver(
                dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php'
            );
        };

        $this->container[CacheResolver::class] = function(ContainerInterface $container) {
            return new CacheResolver(
                $container->get(ClassMapResolver::class),
                new Psr16Cache(new ArrayAdapter())
            );
        };
    }
}
