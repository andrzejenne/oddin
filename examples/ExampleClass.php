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

    public function __construct()
    {
        unset($this->service, $this->exampleService);
    }

    /**
     * @return ExampleService
     */
    public function getService(): ExampleService
    {
        return $this->service;
    }

    /**
     * @return NsExampleService
     */
    public function getExampleService(): NsExampleService
    {
        return $this->exampleService;
    }
}
