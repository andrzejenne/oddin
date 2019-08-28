<?php


use BigBIT\Oddin\Bootstrap;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected $container;

    /**
     */
    protected function setUp(): void
    {
        $this->container = Bootstrap::getContainer();
    }
}
