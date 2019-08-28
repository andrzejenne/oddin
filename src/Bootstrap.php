<?php
namespace BigBIT\Oddin;

use BigBIT\Oddin\Singletons\DIResolver;
use BigBIT\Oddin\Utils\CacheResolver;
use BigBIT\Oddin\Utils\ClassMapResolver;
use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;

/**
 * Class Bootstrap
 * @package BigBIT\Oddin
 */
class Bootstrap
{
    /** @var string */
    public static $autoloadPath = 'vendor/autoload.php';

    /** @var SmartContainer */
    protected static $container;

    /**
     * @param array $bindings
     * @return SmartContainer
     */
    public static function getContainer(array $bindings = []) {
        if (null === static::$container) {
            static::boot($bindings);
        }

        return static::$container;
    }

    /**
     * @param array $bindings
     */
    protected static function boot(array $bindings) {
        require (static::$autoloadPath);

        static::$container = new SmartContainer();

        $bindings = array_merge(static::getDefaultBindings(), $bindings);

        foreach ($bindings as $key => $value) {
            static::$container[$key] = $value;
        }

        static::$container[ContainerInterface::class] = static::$container;

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
                return new ClassMapResolver(static::$autoloadPath);
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
