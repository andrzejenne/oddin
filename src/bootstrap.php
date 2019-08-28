<?php

use BigBIT\Oddin\SmartContainer;
use BigBIT\Oddin\Singletons\DIResolver;

require ('vendor/autoload.php');

$container = new SmartContainer();

DIResolver::create($container);

return $container;

