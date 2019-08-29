<?php


use BigBIT\DIBootstrap\Exceptions\ClassNotFoundException;
use BigBIT\DIBootstrap\Exceptions\InvalidContainerImplementationException;
use BigBIT\DIBootstrap\Exceptions\PathNotFoundException;
use BigBIT\Oddin\Examples\child\ChildClass;
use BigBIT\Oddin\Examples\ExampleContainer;
use BigBIT\Oddin\Examples\ExampleService;
use BigBIT\Oddin\Examples\parent\ParentClass;
use BigBIT\Oddin\Support\composer\Bootstrap;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected $container;

    /**
     * @throws ClassNotFoundException
     * @throws InvalidContainerImplementationException
     * @throws PathNotFoundException
     */
    protected function setUp(): void
    {
        Bootstrap::useContainerImplementation(ExampleContainer::class);
        Bootstrap::useVendorPath(dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'vendor');
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
    }
}
