<?php

namespace Sytzez\DataObjectTester\Tests\Factories;

use Sytzez\DataObjectTester\Factories\ClassExpectationFactory;
use PHPUnit\Framework\TestCase;
use Sytzez\DataObjectTester\Tests\TestHelpers\DataClass;
use Sytzez\DataObjectTester\Tests\TestHelpers\EmptyClass;

class ClassExpectationFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_throws_an_exception_if_a_getter_name_is_not_a_string(): void
    {
        static::expectExceptionMessage('Getter name must be a string, integer given');

        ClassExpectationFactory::create(DataClass::class, [
            1 => ['value'],
        ]);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_getter_values_are_not_an_array(): void
    {
        static::expectExceptionMessage("Getter values for 'getString' must be an array, string given");

        ClassExpectationFactory::create(DataClass::class, [
            'getString' => 'value',
        ]);
    }

    /**
     * @test
     */
    public function it_can_create_an_empty_class_expectation(): void
    {
        $result = ClassExpectationFactory::create(EmptyClass::class, []);

        static::assertEquals(EmptyClass::class, $result->getFqn());
        static::assertEmpty($result->getPropertyExpectations());
    }

    /**
     * @test
     */
    public function it_can_create_a_class_expectation_with_property_expectations(): void
    {
        $result = ClassExpectationFactory::create(DataClass::class, [
            'getString' => ['a', 'b'],
            'getInt'    => [1, 2, 3],
            'getArray'  => [[]],
        ]);

        static::assertEquals(DataClass::class, $result->getFqn());
        static::assertCount(3, $result->getPropertyExpectations());
        static::assertEquals('getString', $result->getPropertyExpectations()[0]->getGetterName());
        static::assertCount(2, $result->getPropertyExpectations()[0]->getCases());
    }
}
