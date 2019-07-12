# oddin
On Demand Dependency InjectioN

## About
If you are using DI in your projects, writing properties and constructors can be "pain".
You have to define class property and initialize it within constructor.
With oddin you can just add class property annotation and use it directly.

## How it works
Class property annotations are parsed. DIResolver uses them to get dependency.
InjectsOnDemand trait defines magic __get method, which handles all property requests, which are undefined.

## Pros
* less coding
* instantiation on demand

## Cons
* all properties are public
* antipattern ?

## Known Issues
* no parent support yet
* no use statement support yet
* no generator for production yet

## Examples
@todo
