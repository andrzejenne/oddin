<?php


namespace BigBIT\Oddin\Examples;


use Psr\Container\ContainerInterface;

/**
 * Class ExampleContainer
 * @package BigBIT\Oddin\Examples
 */
class ExampleContainer implements ContainerInterface, \ArrayAccess
{

    /** @var array */
    private array $definitions = [];

    /** @var array */
    private array $instances = [];

    /**
     * @param string $id
     * @return mixed
     * @throws \Exception
     */
    public function get($id)
    {
        if (!isset($this->instances[$id])) {
            if (isset($this->definitions[$id])) {
                try {
                    $this->instances[$id] = $this->definitions[$id]($this);
                }
                catch (\Throwable $throwable) {
                    throw new \Exception("Cannot resolve dependency for $id", 0, $throwable);
                }
            }
            else {
                throw new \Exception("Definition for $id not found");
            }
        }

        return $this->instances[$id];
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has($id)
    {
        return isset($this->definitions[$id]);
    }

    /**
     * @param string $id
     * @param mixed $instance
     */
    public function bind($id, $instance) {
        $this->instances[$id] = $instance;
        $this->definitions[$id] = true;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * @param mixed $offset
     * @return mixed
     * @throws \Exception
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @throws \Exception
     */
    public function offsetSet($offset, $value)
    {
        if ($offset === 'definitions' || $offset === 'instances') {
            throw new \Exception('Overwriting private properties is forbidden');
        }

        if (is_callable($value)) {
            $this->definitions[$offset] = $value;
        } else {
            $this->definitions[$offset] = true;
            $this->instances[$offset] = $value;
        }
    }

    /**
     * @param mixed $offset
     * @throws \Exception
     */
    public function offsetUnset($offset)
    {
        throw new \Exception('No unset ;)');
    }
}
