<?php
use Slim\DefaultServicesProvider;

$container = require(__DIR__ . '/../../src/bootstrap.php');

$serviceProvider = new DefaultServicesProvider();

$serviceProvider->register($container);

$container[DefaultServicesProvider::class] = $serviceProvider;

return $container;
