<?php


namespace BigBIT\Oddin\Examples\child;


use BigBIT\Oddin\Examples\ExampleService;
use BigBIT\Oddin\Examples\ns\ExampleService as NsExampleService;
use BigBIT\Oddin\Examples\parent\ParentClass;
use BigBIT\Oddin\Traits\InjectsOnDemand;

/**
 * Class ChildClass
 * @package BigBIT\Oddin\Examples\child
 */
class ChildClass extends ParentClass
{
    use InjectsOnDemand;

    /** @var ExampleService */
    private ExampleService $service1;

    /** @var NsExampleService */
    private NsExampleService $service2;

    public function __construct()
    {
        parent::__construct();
        unset($this->service1, $this->service2);
    }


    /**
     * @return ExampleService
     */
    public function getService1() {
        return $this->service1;
    }

    /**
     * @return NsExampleService
     */
    public function getService2() {
        return $this->service2;
    }

}
