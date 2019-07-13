<?php


namespace BigBIT\Oddin\Examples\parent;


use BigBIT\Oddin\Traits\InjectsOnDemand;

/**
 * Class ParentClass
 * @package BigBIT\Oddin\Examples\parent
 * @property ExampleService $parentService
 */
class ParentClass
{
    use InjectsOnDemand; // not necessary if not used
}