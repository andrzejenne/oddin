<?php


namespace BigBIT\Oddin\Examples\exceptions;


use Psr\Container\ContainerExceptionInterface;
use Throwable;

/**
 * Class CannotResolveException
 * @package BigBIT\Oddin\Examples\exeptions
 */
class CannotResolveException extends \Exception implements ContainerExceptionInterface
{
    /**
     * CannotResolveException constructor.
     * @param string $id
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($id = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf("Cannot resolve dependency for %s", $id), $code, $previous);
    }

}
