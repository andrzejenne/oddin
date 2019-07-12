<?php


use BigBIT\Oddin\Utils\CacheResolver;
use BigBIT\Oddin\Utils\SimpleContainer;
use ns\ExampleService as NsExampleService;
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
        $this->container = new SimpleContainer();
        $this->container->define(
            CacheInterface::class,
            new Psr16Cache(new ArrayAdapter())
        );

        $this->container->define(
            CacheResolver::class,
            new CacheResolver($this->container->get(CacheInterface::class))
        );

        $this->container->define(ExampleService::class, new ExampleService());
        $this->container->define(NsExampleService::class, new NsExampleService());

    }
}
