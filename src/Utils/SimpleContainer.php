<?php


namespace BBIT\Oddin\Utils;


use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class SimpleContainer
 * @package BBIT\Oddin\Utils
 */
class SimpleContainer implements ContainerInterface
{
    /**
     * @var array
     */
    private $instances = [];

    /**
     * @param string $id
     * @return mixed
     */
    public function get($id)
    {
        return $this->instances[$id];
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has($id)
    {
        return isset($this->instances[$id]);
    }

    /**
     * @param string $id
     * @param mixed $instance
     */
    public function define($id, $instance) {
        $this->instances[$id] = $instance;
    }

}
