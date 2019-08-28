<?php
namespace BigBIT\Oddin\Support;

use BigBIT\Oddin\Bootstrap;
use Slim\DefaultServicesProvider;
use Slim\Collection;

/**
 * Class SlimBootstrap
 * @package BigBIT\Oddin\Support
 */
class Slim3Bootstrap extends Bootstrap {
    public static $defaultSettings = [
        'httpVersion' => '1.1',
        'responseChunkSize' => 4096,
        'outputBuffering' => 'append',
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => false,
        'addContentLengthHeader' => true,
        'routerCacheFile' => false,
    ];

    /**
     * @param array $bindings
     */
    final protected static function boot(array $bindings) {
        $userSettings = &$bindings['settings'];

        $bindings['settings'] = function() use ($userSettings) {
            return new Collection(array_merge(static::$defaultSettings, $userSettings));
        };

        parent::boot($bindings);
        $serviceProvider = new DefaultServicesProvider();

        $serviceProvider->register(static::$container);

        $container[DefaultServicesProvider::class] = $serviceProvider;
    }
}
