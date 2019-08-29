<?php


namespace BigBIT\Oddin\Exceptions;


use Throwable;

/**
 * Class InvalidContainerImplementationException
 * @package BigBIT\Oddin\Exceptions
 */
class InvalidContainerImplementationException extends \Exception
{
    /**
     * InvalidContainerImplementationException constructor.
     * @param $implementation
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($implementation, $code = 0, Throwable $previous = null)
    {
        parent::__construct(get_class($implementation) . " does not implements Psr\Container\ContainerInterface", $code, $previous);
    }

}
