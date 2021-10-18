# Data Object Tester

Helps automate automated tests in PHPUnit for immutable data objects.

### Requirements

- PHP 8.0 or above
- PHPUnit

## Installation

`composer require sytzez/data-object-tester --dev`

## Usage

Consider the following data class:

```php
final class DataClass
{
    public function __construct(
        private string $string,
        private int $int,
        private array $array,
    ) {
    }

    public function getString(): string
    {
        return $this->string;
    }

    public function getInt(): int
    {
        return $this->int;
    }

    public function getArray(): array
    {
        return $this->array;
    }
}
```

To make sure the getter methods always return the given values, all you need to do is write a test that extends the DataObjectTestCase:

```php
use Sytzez\DataObjectTester\DataObjects\ClassExpectation
use Sytzez\DataObjectTester\DataObjectTestCase

class DataClassTest extends DataObjectTestCase
{
    /**
     * @test
     */
    public function it_returns_the_right_values(): void
    {
        $this->testDataObjects(
            ClassExpectation::create(DataClass::class, [
                'getString' => ['hello', 'world'],
                'getInt'    => [0, -1, PHP_INT_MAX],
                'getArray'  => [['a', 'b', 'c'], [1, 2, 3]],
            ])
        );
    }
}
```

This will test that all the getters exist, and that they give back the values provided in the constructor.

The array in the class expectation lists the getters, in the same order as their respective constructor arguments.
For each property, any number of possible values can be given. 
The tester will construct a couple of objects using those values, and assert that the getters return the right values.

## Advanced features

### Testing optional arguments

If an argument can be optional (having a default value), you can use a `DefaultPropertyCase` to define the default expected value.

```php
use Sytzez\DataObjectTester\DataObjects\ClassExpectation
use Sytzez\DataObjectTester\PropertyCases\DefaultPropertyCase;

ClassExpectation::create(ValidatedDataClass::class, [
    'getNumber' => [
        1, 
        10,
        new DefaultPropertyCase(0),
    ],
]),
```

### Testing validation exceptions

If passing certain values should cause an error or exception to be thrown during instantiation,
use the `ConstructorExceptionPropertyCase`. So if your constructor looks like this;

```php
public function __construct(
    private int $number,
) {
    if ($number < 0) {
        throw new \InvalidArgumentException('Number cannot be negative');
    }
}
```

You can test it like this:

```php
use Sytzez\DataObjectTester\DataObjects\ClassExpectation
use Sytzez\DataObjectTester\PropertyCases\ConstructorExceptionPropertyCase;

ClassExpectation::create(ValidatedDataClass::class, [
    'getNumber' => [
        1, 
        10,
        new ConstructorExceptionPropertyCase(-1, 'Number cannot be negative'),
    ],
]),
```

If multiple arguments should cause an exception, the test will assert that one the possible exceptions will be thrown.

### Testing transformative properties

If your data class alters the passed in values in some way, you can use the `TransformativePropertyCase` class in your class expectation.

Let's say your getter looks like this:

```php
public function getString(): string
{
    return ucfirst($this->string);
}
```

Then you could write the class expectation like this:

```php
use Sytzez\DataObjectTester\DataObjects\ClassExpectation
use Sytzez\DataObjectTester\PropertyCases\TransformativePropertyCase;

ClassExpectation::create(TransformativeDataClass::class, [
    'getString' => [
        new TransformativePropertyCase('hello', 'Hello'),
        new TransformativePropertyCase('world', 'World'),
    ],
]),
```

### Test case generator

By default, a minimal amount of objects is created, covering each specified property value at least once.
But if you wish to cover every possible combination of property values, you can use a `MaximalCaseGenerator`,
by passing it as the second argument to `testDataObjects`, like so:

```php
use Sytzez\DataObjectTester\Generators\MaximalCaseGenerator

$this->testDataObjects(
    ClassExpectation::create(DataClass::class, [
        'getString' => ['hello', 'world'],
        'getInt'    => [0, -1, PHP_INT_MAX],
        'getArray'  => [['a', 'b', 'c'], [1, 2, 3]],
    ]),
    new MaximalCaseGenerator()
);
```

In this case, 12 (2 * 3 * 2) objects will be instantiated and tested, instead of the minimum of 3 cases.

You can cap the number of objects by providing an argument to the generator, e.g. `new MaximalCaseGenerator(20)`.
The default cap is 100.

You can also provide your own case generators by implementing the [CaseGeneratorStrategy](src/Contracts/Generators/CaseGeneratorStrategy.php).



## TODO

- Handling optional arguments (needs work: in case generators)
- Unit test every class
- Setup github actions
