<?php


use BBIT\Oddin\Traits\InjectsOnDemand;

/**
 * Class ExampleClass
 * @property ExampleService $service
 * @property ns\ExampleService $exampleService
 */
class ExampleClass
{
    use InjectsOnDemand;
}
