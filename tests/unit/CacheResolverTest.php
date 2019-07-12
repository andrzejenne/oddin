<?php


use BBIT\Oddin\Utils\ServiceResolver;
use ns\ExampleService as NsExampleService;

/**
 * Class CacheResolverTest
 */
class CacheResolverTest extends TestCase
{


    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testServiceResolver()
    {
        $serviceResolver = new ServiceResolver(ExampleClass::class, $this->container);

        $instance = $serviceResolver->get('service');

        $this->assertInstanceOf(ExampleService::class, $instance);

        $instance = $serviceResolver->get('exampleService');
        $this->assertInstanceOf(NsExampleService::class, $instance);
    }

}
