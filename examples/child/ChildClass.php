<?php


namespace BigBIT\Oddin\Examples\child;


use BigBIT\Oddin\Examples\ExampleService;
use BigBIT\Oddin\Examples\ns\ExampleService as NsExampleService;
use BigBIT\Oddin\Examples\parent\ParentClass;
use BigBIT\Oddin\Traits\InjectsOnDemand;

/**
 * Class ChildClass
 * @package BigBIT\Oddin\Examples\child
 * @property ExampleService $service1
 * @property NsExampleService $service2
 */
class ChildClass extends ParentClass
{
    use InjectsOnDemand;

}