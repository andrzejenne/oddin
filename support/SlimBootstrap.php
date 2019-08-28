<?php
namespace BigBIT\Oddin\Support;

use BigBIT\Oddin\Bootstrap;
use Slim\DefaultServicesProvider;

/**
 * Class SlimBootstrap
 * @package BigBIT\Oddin\Support
 */
class SlimBootstrap extends Bootstrap {
    final protected static function boot(array $bindings) {
        parent::boot($bindings);
        $serviceProvider = new DefaultServicesProvider();

        $serviceProvider->register(static::$container);

        $container[DefaultServicesProvider::class] = $serviceProvider;
    }
}
