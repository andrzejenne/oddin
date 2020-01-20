<?php


use BigBIT\Oddin\Examples\child\DeprecatedChildClass;
use BigBIT\Oddin\Examples\DeprecatedExampleClass;
use BigBIT\Oddin\Examples\ExampleService;
use BigBIT\Oddin\Examples\ns\ExampleService as NsExampleService;
use BigBIT\Oddin\Examples\parent\ExampleService as ParentExampleService;
use BigBIT\Oddin\Singletons\DIResolver;

/**
 * Class CacheResolverTest
 */
class DeprecatedDIResolverTest extends DeprecatedTestCase
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
        $example = new DeprecatedExampleClass();

        $this->assertInstanceOf(ExampleService::class, $example->service);
        $this->assertInstanceOf(NsExampleService::class, $example->exampleService);
    }

    public function testInheritance()
    {
        $child = new DeprecatedChildClass();

        $this->assertInstanceOf(ExampleService::class, $child->service1);
        $this->assertInstanceOf(NsExampleService::class, $child->service2);
        $this->assertInstanceOf(ParentExampleService::class, $child->parentService);

    }

}
