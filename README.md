# oddin - On Demand Dependency INjection

## About
Sometimes coding is a pain. Your boss needs it now or simply you are bored writing all 
sugar again and again and ...
If you are using DI in your projects, you have to write property declarations and initialize them in constructors.
You can use dependency container or define injectable constructor arguments.
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
With oddin you can skip declaration and constructor part. Just add class property annotations and access properties anytime you need. 
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
I assume all PHP IDEs with autocomplete will work with our approach.

## How it works
PHP classes has magic methods. The __get magic method is invoked every time you want to use inaccessible property.
Property can be undeclared or private/protected from outside.
DIResolver uses parser to get dependency necessary metadata from class property annotations.
InjectsOnDemand trait defines magic __get method, which handles all our property requests.
Once property is initialized by trait, magic method is not called again.

## Pros
* less coding
* dependency instantiation on demand (lazy - not before constructor, if properly defined in container)

## Cons
* all properties are public ? all injectables are "public"
* antipattern ? use it only for sprints, clean the code later.
* annotations have to be parsed ? oh, come on, we are caching it, generator on it's way.

## Known Issues
* no cache generator yet
* no code fixer yet

## @TODO - Cache Generator
Cli command for annotation cache generation.

## @TODO - Code Fixer
Cli command for fixing code. It will remove class property annotations, 
declare properties and add constructor or getters and container to constructor. 
