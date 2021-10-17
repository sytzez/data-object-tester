<?php

namespace Sytzez\DataObjectTester\Tests\DataObjects;

use Sytzez\DataObjectTester\DataObjects\ClassExpectation;
use Sytzez\DataObjectTester\DataObjects\InputOutputExpectation;
use Sytzez\DataObjectTester\DataObjects\ObjectCase;
use Sytzez\DataObjectTester\DataObjects\PropertyCase;
use Sytzez\DataObjectTester\DataObjects\PropertyExpectation;
use Sytzez\DataObjectTester\DataObjectTestCase;
use Sytzez\DataObjectTester\Tests\TestHelpers\DataClass;
use Sytzez\DataObjectTester\Tests\TestHelpers\EmptyClass;

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
            new PropertyCase(
                $properties[0],
                new InputOutputExpectation('a', 'a'),
            ),
            new PropertyCase(
                $properties[1],
                new InputOutputExpectation(1, 1),
            ),
            new PropertyCase(
                $properties[2],
                new InputOutputExpectation([], []),
            ),
        ];

        $objectCase = new ObjectCase($expectation, $propertyCases);

        static::assertEquals($expectation, $objectCase->getClassExpectation());
        static::assertEquals($propertyCases, $objectCase->getPropertyCases());
        static::assertEquals(['a', 1, []], iterator_to_array($objectCase->getConstructorArguments()));
    }

    /**
     * @test
     */
    public function it_can_receive_the_property_cases_in_any_order(): void
    {
        $expectation = ClassExpectation::create(DataClass::class, [
            'getString' => ['a', 'b', 'c'],
            'getInt'    => [1, 2, 3],
            'getArray'  => [[]],
        ]);

        $properties = $expectation->getPropertyExpectations();

        $propertyCases = [
            new PropertyCase(
                $properties[2],
                new InputOutputExpectation([], []),
            ),
            new PropertyCase(
                $properties[0],
                new InputOutputExpectation('a', 'a'),
            ),
            new PropertyCase(
                $properties[1],
                new InputOutputExpectation(1, 1),
            ),
        ];

        $objectCase = new ObjectCase($expectation, $propertyCases);

        static::assertEquals($expectation, $objectCase->getClassExpectation());
        static::assertEquals($propertyCases, $objectCase->getPropertyCases());
        static::assertEquals(['a', 1, []], iterator_to_array($objectCase->getConstructorArguments()));
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
            new PropertyCase(
                $properties[0],
                new InputOutputExpectation('a', 'a'),
            ),
            new PropertyCase(
                $properties[1],
                new InputOutputExpectation(1, 1),
            ),
        ];

        static::expectExceptionMessage("Getter 'getArray' has no provided case");

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
            new PropertyCase(
                $properties[0],
                new InputOutputExpectation('a', 'a'),
            ),
            new PropertyCase(
                $properties[1],
                new InputOutputExpectation(1, 1),
            ),
            new PropertyCase(
                $properties[2],
                new InputOutputExpectation([], []),
            ),
            new PropertyCase(
                new PropertyExpectation('getSomethingElse', []),
                new InputOutputExpectation(123, 456),
            ),
        ];

        static::expectExceptionMessage("Getter 'getSomethingElse' does not exist on class expectation");

        new ObjectCase($expectation, $propertyCases);
    }
}
