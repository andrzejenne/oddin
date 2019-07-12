<?php


use BigBIT\Oddin\Singletons\DIResolver;
use ns\ExampleService as NsExampleService;

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

        $this->assertInstanceOf(ExampleService::class, $example->service);
        $this->assertInstanceOf(NsExampleService::class, $example->exampleService);
    }

}
