<?php


namespace BigBIT\Oddin\Examples\exceptions;


use Psr\Container\NotFoundExceptionInterface;
use Throwable;

/**
 * Class DefinitionNotFoundException
 * @package BigBIT\Oddin\Examples\exeptions
 */
class DefinitionNotFoundException extends \Exception implements NotFoundExceptionInterface
{
    /**
     * DefinitionNotFoundException constructor.
     * @param string $id
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($id = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf("Definition for %s not found", $id), $code, $previous);
    }


}
