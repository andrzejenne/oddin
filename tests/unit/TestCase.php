<?php


use BigBIT\Oddin\SmartContainer;
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
        $this->container = new SmartContainer();

        $this->container[CacheInterface::class] = function () {
            return new Psr16Cache(new ArrayAdapter());
        };

        $this->container[CacheResolver::class] = function (ContainerInterface $container) {
            return new CacheResolver(
                $container[CacheInterface::class],
                new ClassMapResolver(
                    realpath(__DIR__ . DIRECTORY_SEPARATOR
                        . '..' . DIRECTORY_SEPARATOR
                        . '..' . DIRECTORY_SEPARATOR)
                )
            );
        };
    }
}
