<?php

use BigBIT\Oddin\SmartContainer;
use BigBIT\Oddin\Singletons\DIResolver;

require ('vendor/autoload.php');

DIResolver::create(new SmartContainer());

