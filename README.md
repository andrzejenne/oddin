# ODDIN - On Demand Dependency INjection

## About
Sometimes coding is a pain. Your boss needs it now or simply you are bored writing all 
sugar again and again and ...

If you are using DI in your projects, you have to write property declarations and initialize them in constructors.
You can use dependency container or define injectable constructor arguments. It depends on framework used.
```php
class Foo {
    /** @var Dependency1 */
    private $dep1;
    
    /** @var Dependency2 */
    private $dep2;
    
    /** @var Depencency3 */
    private $dep3;
    
    function __construct(Dependency1 $dep1, Dependency2 $dep2, Container $container)
    {
        $this->dep1 = $dep1;
        $this->dep2 = $dep2;
        $this->dep3 = $container->get(Dependency3::class);
    }
    
    public function bar() 
    {
        $this->dep1->doSomething();
    }
    
    protected function baz()
    {
        $this->dep2->doSomethind();
    }
    
    private function bye() 
    {
        $this->dep3->doSomething();
    }
}

```
With ODDIN you can skip declaration and constructor part. Just add class property annotations and access properties anytime you need. 
```php
/**
 * @property Dependency1 $dep1
 * @property Dependency2 $dep2
 * @property Dependency3 $dep3
 */
class Foo {
    use InjectsOnDemand;
    
    public function bar() 
    {
        $this->dep1->doSomething();
    }
    
    private function baz() 
    {
        $this->dep2->doSomething();
    }
    
    private function bye()
    {
        $this->dep3->doSomethind();
    }
}
```
I assume all PHP IDEs with autocomplete will work with ODDIN approach.

## How it works
PHP classes can have magic methods. The __get magic method is invoked every time you want to use inaccessible property.
Property can be undeclared or private/protected from outside.
DIResolver uses parser to get dependency metadata from class property annotations.
InjectsOnDemand trait defines magic __get method, which handles all our property requests.
Once property is initialized by trait, magic method is not called again.

## Builtin SmartContainer
This DI Container can automagically instantiate classes. This helps to avoid class map definitions.
There are special use cases, where you have to manually define class maps.
@todo - research configuration possibilities to help deal with such use cases.

## Pros
* less coding
* dependency instantiation on demand (lazy - not before constructor, if properly defined in DI container)

## Cons
* all properties are public ? all injectables are "public"
* antipattern ? use it only for prototyping, clean the code later.
* annotations have to be parsed ? oh, come on, we are caching it, generator planned.

## Purpouse
Cleaner controller classes, less resource demanding. But it's up to you, where you use ODDIN.

## Known Issues
* no code fixer yet

## Quick start
You can use any DI container, which implements Psr\Container\ContainerInterface.
For quick start, you can use Bootstrap class, which uses SmartContainer.
```php
use BigBIT\Oddin\Bootstrap;
use Psr\Container\ContainerInterface;

// custom bindings
$bindings = [
    FooInterface::class => function(ContainerInterface $container) {
    return new BarImplementation(
        $container->get(BazDependency::class)
    );
];

$container = Bootstrap::getContainer($bindings);

$app = new SomeApp($container);

$app->run();
```
### Slim v3
For Slim version 3 support bootstrap was added. It's in separate package bigbit/smart-di-slim.
```bash
composer require bigbit/smart-di-slim
```

```php
use BigBIT\SmartDI\Support\slim\Bootstrap;
use Psr\Container\ContainerInterface;

$container = Bootstrap::getContainer($bindings);

$app = new Slim\App($container);

$app->run();
```
### Other frameworks
You can request other frameworks support or write you own bootstrap based on Bootstrap class.

## PHP-DI comparison
@todo

## Cache Generator
Experimental implementation of cache generator was added. If your project has phpstan installed, it's recommended
to install tracy/tracy as well.
Oddin uses Psr\SimpleCache\CacheInterface implementations and Symfony as default.

Creating cache is not necessary, but recommended for production environments:
```bash
vendor/bin/oddin cache:injectables:create php-files -a oddin -a 0 -a cache
```
Arguments for cli commands are derived from adapter constructor.

Instantiating cache:
```php
$bindings[CacheInterface::class] = function(ContainerInterface $container) {
    return new Psr16Cache(new PhpFilesAdapter('oddin', 0, dirname(__DIR__) . '/cache'));
};
```

## @TODO - Code Fixer
Cli command for fixing code. It will remove class property annotations, 
declare properties and add constructor or getters and container to constructor. 
