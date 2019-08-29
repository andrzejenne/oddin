<?php


use BigBIT\Oddin\Examples\ExampleContainer;
use BigBIT\Oddin\Support\composer\Bootstrap;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected $container;

    /**
     * @throws \BigBIT\Oddin\Exceptions\ClassNotFoundException
     * @throws \BigBIT\Oddin\Exceptions\InvalidContainerImplementationException
     * @throws \BigBIT\Oddin\Exceptions\PathNotFoundException
     */
    protected function setUp(): void
    {
        Bootstrap::useContainerImplementation(ExampleContainer::class);
        Bootstrap::useVendorPath(dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'vendor');
        $this->container = Bootstrap::getContainer();
    }
}
