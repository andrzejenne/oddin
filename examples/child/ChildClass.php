<?php


namespace BigBIT\Oddin\Examples\child;


use BigBIT\Oddin\Examples\ExampleService;
use BigBIT\Oddin\Examples\ns\ExampleService as NsExampleService;
use BigBIT\Oddin\Examples\parent\ParentClass;

/**
 * Class ChildClass
 * @package BigBIT\Oddin\Examples\child
 */
class ChildClass extends ParentClass
{
    /** @var ExampleService */
    private ExampleService $service1;

    /** @var NsExampleService */
    private NsExampleService $service2;

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
