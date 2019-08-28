<?php
namespace BigBIT\Oddin;

use BigBIT\Oddin\Singletons\DIResolver;

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

        foreach ($bindings as $key => $value) {
            static::$container[$key] = $value;
        }

        DIResolver::create(static::$container);
    }
}
