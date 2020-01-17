<?php


namespace BigBIT\Oddin\Examples\parent;


use BigBIT\Oddin\Traits\InjectsOnDemand;

/**
 * Class ParentClass
 * @package BigBIT\Oddin\Examples\parent
 */
class ParentClass
{
    use InjectsOnDemand; // not necessary if not used

    /** @var ExampleService */
    private ExampleService $parentService;

    /**
     * @return ExampleService
     */
    public function getParentService()
    {
        return $this->parentService;
    }
}
