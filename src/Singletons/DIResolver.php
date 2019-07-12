<?php


namespace BBIT\Oddin\Singletons;


use BBIT\Oddin\Utils\ServiceResolver;
use Psr\Container\ContainerInterface;

/**
 * Class DIResolver
 * @package BBIT\Oddin\Singletons
 */
class DIResolver
{
    /** @var DIResolver */
    static $instance;

    /** @var ContainerInterface */
    private $container;

    /** @var ServiceResolver[] */
    private $resolvers;

    /**
     * Resolver constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param ContainerInterface $container
     * @return DIResolver
     */
    final public static function create(ContainerInterface $container) {
        static::$instance = new static($container);

        return static::$instance;
    }

    /**
     * @param mixed $object
     * @return ServiceResolver
     */
    final public static function for($object) {
        return static::$instance->getResolver($object);
    }

    /**
     * @param mixed $object
     * @return ServiceResolver
     */
    private function getResolver($object) {
        $cls = get_class($object);
        if (!isset($this->resolvers[$cls])) {
            $this->resolvers[$cls] = new ServiceResolver($cls, $this->container);
        }

        return $this->resolvers[$cls];
    }
}
