<?php
namespace BigBIT\Oddin\Support\composer;

use BigBIT\Oddin\Exceptions\ClassNotFoundException;
use BigBIT\Oddin\Exceptions\InvalidContainerImplementationException;
use BigBIT\Oddin\Exceptions\PathNotFoundException;
use BigBIT\Oddin\Singletons\DIResolver;
use BigBIT\Oddin\Utils\CacheResolver;
use BigBIT\Oddin\Utils\ClassMapResolver;
use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;

/**
 * Class Bootstrap
 * @package BigBIT\Oddin\Support\composer
 */
class Bootstrap extends \BigBIT\DIBootstrap\Bootstrap
{
    /**
     * @param array $bindings
     * @throws \BigBIT\DIBootstrap\Exceptions\ClassNotFoundException
     * @throws \BigBIT\DIBootstrap\Exceptions\InvalidContainerImplementationException
     * @throws \BigBIT\DIBootstrap\Exceptions\PathNotFoundException
     */
    protected static function boot(array $bindings) {
        parent::boot($bindings);

        DIResolver::create(static::$container);
    }

    /**
     * @return array
     */
    private static function getDefaultBindings() {
        return [
            CacheInterface::class => function () {
                return new Psr16Cache(new ArrayAdapter());
            },
            ClassMapResolver::class => function () {
                return new ClassMapResolver(static::getAutoloadPath());
            },
            CacheResolver::class => function (ContainerInterface $container) {
                return new CacheResolver(
                    $container->get(ClassMapResolver::class),
                    $container->get(CacheInterface::class)
                );
            },
        ];
    }
}
