<?php

namespace BigBIT\Oddin\Examples;

use BigBIT\Oddin\Traits\InjectsOnDemand;

use BigBIT\Oddin\Examples\ns\ExampleService as NsExampleService;

/**
 * Class ExampleClass
 * @package BigBIT\Oddin\Examples
 */
class ExampleClass
{
    use InjectsOnDemand;

    /** @var ExampleService */
    private ExampleService $service;

    /** @var NsExampleService */
    private NsExampleService $exampleService;
}
