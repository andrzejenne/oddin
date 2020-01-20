<?php


use BigBIT\Oddin\Examples\child\ChildClass;
use BigBIT\Oddin\Examples\ExampleClass;
use BigBIT\Oddin\Examples\ExampleService;
use BigBIT\Oddin\Examples\ns\ExampleService as NsExampleService;
use BigBIT\Oddin\Examples\parent\ExampleService as ParentExampleService;
use BigBIT\Oddin\Singletons\DIResolver;

/**
 * Class CacheResolverTest
 */
class DIResolverTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        DIResolver::create($this->container);
    }

    /**
     */
    public function testServiceResolver()
    {
        $example = new ExampleClass();

        $this->assertInstanceOf(ExampleService::class, $example->getService());
        $this->assertInstanceOf(NsExampleService::class, $example->getExampleService());
    }

    public function testInheritance()
    {
        $child = new ChildClass();

        $this->assertInstanceOf(ExampleService::class, $child->getService1());
        $this->assertInstanceOf(NsExampleService::class, $child->getService2());
        $this->assertInstanceOf(ParentExampleService::class, $child->getParentService());

    }
}
