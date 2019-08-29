<?php
namespace BigBIT\Oddin\Support\composer;

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
     * @param ContainerInterface $container
     * @param array $bindings
     */
    protected static function bootContainer(ContainerInterface $container, array $bindings) {
        parent::bootContainer($container, $bindings);

        DIResolver::create(static::$container);
    }



    /**
     * @return array
     */
    protected static function getDefaultBindings() {
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
