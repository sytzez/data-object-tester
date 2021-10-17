<?php

namespace Sytzez\DataObjectTester\Tests\DataObjects;

use Sytzez\DataObjectTester\DataObjects\ClassExpectation;
use Sytzez\DataObjectTester\DataObjects\InputOutputExpectation;
use Sytzez\DataObjectTester\DataObjects\ObjectCase;
use Sytzez\DataObjectTester\DataObjects\PropertyCase;
use Sytzez\DataObjectTester\DataObjectTestCase;
use Sytzez\DataObjectTester\PropertyCases\SimplePropertyCase;
use Sytzez\DataObjectTester\Tests\TestHelpers\DataClass;
use Sytzez\DataObjectTester\Tests\TestHelpers\EmptyClass;
use Sytzez\DataObjectTester\Tests\TestHelpers\GeneratorToArray;

class ObjectCaseTest extends DataObjectTestCase
{
    /**
     * @test
     */
    public function it_can_create_a_case_for_an_empty_class(): void
    {
        $expectation = ClassExpectation::create(EmptyClass::class, []);

        $objectCase = new ObjectCase($expectation, []);

        static::assertEquals($expectation, $objectCase->getClassExpectation());
        static::assertEquals([], $objectCase->getPropertyCases());
        static::assertEquals(0, iterator_count($objectCase->getConstructorArguments()));
    }

    /**
     * @test
     */
    public function it_can_create_a_case_for_a_class_with_getters(): void
    {
        $expectation = ClassExpectation::create(DataClass::class, [
            'getString' => ['a', 'b', 'c'],
            'getInt'    => [1, 2, 3],
            'getArray'  => [[]],
        ]);

        $properties = $expectation->getPropertyExpectations();

        $propertyCases = [
            (new SimplePropertyCase('a'))
                ->setGetterName('getString'),
            (new SimplePropertyCase(1))
                ->setGetterName('getInt'),
            (new SimplePropertyCase([]))
                ->setGetterName('getArray'),
        ];

        $objectCase = new ObjectCase($expectation, $propertyCases);

        static::assertEquals($expectation, $objectCase->getClassExpectation());
        static::assertEquals($propertyCases, $objectCase->getPropertyCases());
        static::assertEquals(['a', 1, []], GeneratorToArray::convert($objectCase->getConstructorArguments()));
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_the_property_cases_are_incomplete(): void
    {
        $expectation = ClassExpectation::create(DataClass::class, [
            'getString' => ['a', 'b', 'c'],
            'getInt'    => [1, 2, 3],
            'getArray'  => [[]],
        ]);

        $properties = $expectation->getPropertyExpectations();

        $propertyCases = [
            (new SimplePropertyCase('a'))
                ->setGetterName('getString'),
            (new SimplePropertyCase(1))
                ->setGetterName('getInt'),
        ];

        static::expectExceptionMessage('Number of property cases on object case (2) does not equal number of properties on class expectation (3)');

        new ObjectCase($expectation, $propertyCases);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_there_is_a_property_case_for_a_nonexistent_property(): void
    {
        $expectation = ClassExpectation::create(DataClass::class, [
            'getString' => ['a', 'b', 'c'],
            'getInt'    => [1, 2, 3],
            'getArray'  => [[]],
        ]);

        $properties = $expectation->getPropertyExpectations();

        $propertyCases = [
            (new SimplePropertyCase('a'))
                ->setGetterName('getString'),
            (new SimplePropertyCase(1))
                ->setGetterName('getInt'),
            (new SimplePropertyCase([]))
                ->setGetterName('getArray'),
            (new SimplePropertyCase(null))
                ->setGetterName('getSomethingElse'),
        ];

        static::expectExceptionMessage('Number of property cases on object case (4) does not equal number of properties on class expectation (3)');

        new ObjectCase($expectation, $propertyCases);
    }
}
