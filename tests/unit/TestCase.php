<?php


use BigBIT\Oddin\Examples\ExampleContainer;
use BigBIT\Oddin\Examples\ExampleService;
use BigBIT\Oddin\Examples\ns\ExampleService as NsExampleService;
use BigBIT\Oddin\Examples\parent\ExampleService as ParentExampleService;
use BigBIT\Oddin\Utils\CacheResolver;
use BigBIT\Oddin\Utils\ClassMapResolver;
use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected $container;

    /**
     */
    protected function setUp(): void
    {
        $this->container = new ExampleContainer();

        $this->container[CacheInterface::class] = function () {
            return new Psr16Cache(new ArrayAdapter());
        };

        $this->container[CacheResolver::class] = function (ContainerInterface $container) {
            return new CacheResolver(
                new ClassMapResolver(), $container[CacheInterface::class]
            );
        };

        $this->container[ExampleService::class] = function () {
            return new ExampleService();
        };

        $this->container[NsExampleService::class] = function () {
            return new NsExampleService();
        };

        $this->container[ParentExampleService::class] = function () {
            return new ParentExampleService();
        };

    }
}
