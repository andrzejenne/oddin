<?php


namespace BigBIT\Oddin\Support\laravel;


use BigBIT\Oddin\Singletons\DIResolver;
use Psr\Container\ContainerInterface;

/**
 * Class Provider
 * @package BigBIT\Oddin\Support\laravel
 */
class Provider
{
    /**
     * Provider constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        DIResolver::create($container);
    }

}
