<?php

namespace BigBIT\Oddin\Examples;

use BigBIT\Oddin\Traits\InjectsOnDemand;

use BigBIT\Oddin\Examples\ns\ExampleService as NsExampleService;

/**
 * Class ExampleClass
 * @package BigBIT\Oddin\Examples
 * @property ExampleService $service
 * @property NsExampleService $exampleService
 */
class DeprecatedExampleClass
{
    use InjectsOnDemand;
}
