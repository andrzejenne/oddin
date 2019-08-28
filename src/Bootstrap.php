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
     * @return SmartContainer
     */
    public static function getContainer() {
        if (!static::$container) {
            static::boot();
        }

        return static::$container;
    }

    protected static function boot() {
        require (static::$autoloadPath);

        static::$container = new SmartContainer();

        DIResolver::create(static::$container);
    }
}
