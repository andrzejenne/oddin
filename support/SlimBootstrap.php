<?php
namespace BigBIT\Oddin\Support;

use BigBIT\Oddin\Bootstrap;
use Slim\DefaultServicesProvider;

class SlimBootstrap extends Bootstrap {
    final protected static function boot() {
        parent::boot();
        $serviceProvider = new DefaultServicesProvider();

        $serviceProvider->register(static::$container);

        $container[DefaultServicesProvider::class] = $serviceProvider;
    }
}
