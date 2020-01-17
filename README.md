# ODDIN - On Demand Dependency INjection

## About
Sometimes coding is a pain. Your boss needs it now or simply you are bored writing all 
sugar again and again and ...

If you are using DI in your projects, you have to write property declarations and initialize them in constructors.
You can use dependency container or define injectable constructor arguments. It depends on framework used.
For of PHP7.4.0 only, 7.4.1 breaks functionality, php bug #78904.
```php
class Foo {
    private Dependency1 $dep1;
    
    private Dependency2 $dep2;
    
    private Depencency3 $dep3;
    
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
With ODDIN you can skip constructor part. Just declare properties and access properties anytime you need.
Keep in mind those properties becomes accessible from anywhere by magic __get method. Your preferred IDE will help you 
deal with that problem
```php
class Foo {
    use InjectsOnDemand;
    
    private Dependency1 $dep1;
    
    private Dependency2 $dep2;
    
    private Depencency3 $dep3;

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

## How it works
PHP classes can have magic methods. The __get magic method is invoked every time you want to use inaccessible property.
Property can be undeclared or private/protected from outside.
DIResolver uses parser to get dependency metadata from class or deprecated property annotations.
InjectsOnDemand trait defines magic __get method, which handles all our property requests.
Once property is initialized by trait, magic method is not called again.

## Pros
* less coding
* dependency instantiation on demand (lazy - not before constructor, if properly defined in DI container)

## Cons
* all properties becomes public ? all injectables are "public"
* antipattern ? use it only for prototyping, clean the code later.

## Purpouse
Cleaner controller classes, less resource demanding. But it's up to you, where you use ODDIN.

## Known Issues
* no code fixer yet

## Quick start
You can use any DI container, which implements Psr\Container\ContainerInterface.
For quick start, you can use Bootstrap class, which uses SmartContainer.
```php
use BigBIT\DIBootstrap\Bootstrap;
use Psr\Container\ContainerInterface;

// custom bindings
$bindings = [
    FooInterface::class => function(ContainerInterface $container) {
        return new BarImplementation(
                $container->get(BazDependency::class)
            );
        }
];

$container = Bootstrap::getContainer($bindings);

$app = new SomeApp($container);

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
