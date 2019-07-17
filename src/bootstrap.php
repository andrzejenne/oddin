<?php

use BigBIT\Oddin\Examples\ExampleContainer;
use BigBIT\Oddin\Singletons\DIResolver;

require ('vendor/autoload.php');

DIResolver::create(new ExampleContainer());

