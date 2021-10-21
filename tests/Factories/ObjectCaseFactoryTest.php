<?php

namespace Sytzez\DataObjectTester\Tests\Factories;

use Sytzez\DataObjectTester\DataObjects\ClassExpectation;
use Sytzez\DataObjectTester\Factories\ObjectCaseFactory;
use PHPUnit\Framework\TestCase;
use Sytzez\DataObjectTester\PropertyCases\DefaultPropertyCase;
use Sytzez\DataObjectTester\PropertyCases\SimplePropertyCase;
use Sytzez\DataObjectTester\Tests\TestHelpers\DataClass;
use Sytzez\DataObjectTester\Tests\TestHelpers\EmptyClass;

class ObjectCaseFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_throws_an_exception_if_a_getter_name_is_not_a_string(): void
    {
        $classExpectation = ClassExpectation::create(DataClass::class, [
            'getString' => ['a'],
            'getInt'    => [1],
            'getArray'  => [[]],
        ]);

        static::expectExceptionMessage('Getter name must be a string, integer given');

        ObjectCaseFactory::create($classExpectation, [
            1 => 'a',
        ]);
    }

    /**
     * @test
     */
    public function it_can_create_an_empty_object_case(): void
    {
        $classExpectation = ClassExpectation::create(EmptyClass::class, []);

        $result = ObjectCaseFactory::create($classExpectation, []);

        static::assertEquals($classExpectation, $result->getClassExpectation());
        static::assertEmpty($result->getPropertyCases());
    }

    /**
     * @test
     */
    public function it_can_create_a_object_case_with_property_cases(): void
    {
        $classExpectation = ClassExpectation::create(DataClass::class, [
            'getString' => ['a', 'b', 'c'],
            'getInt'    => [1, 2],
            'getArray'  => [[], new DefaultPropertyCase([])],
        ]);

        $result = ObjectCaseFactory::create($classExpectation, [
            'getString' => 'a',
            'getInt'    => 2,
            'getArray'  => new DefaultPropertyCase([]),
        ]);

        static::assertEquals($classExpectation, $result->getClassExpectation());
        static::assertCount(3, $result->getPropertyCases());
        static::assertInstanceOf(SimplePropertyCase::class, $result->getPropertyCases()[0]);
        static::assertInstanceOf(SimplePropertyCase::class, $result->getPropertyCases()[1]);
        static::assertInstanceOf(DefaultPropertyCase::class, $result->getPropertyCases()[2]);
    }
}
