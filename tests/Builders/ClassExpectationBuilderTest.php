<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Tests\Builders;

use Sytzez\DataObjectTester\Builders\ClassExpectationBuilder;
use PHPUnit\Framework\TestCase;
use Sytzez\DataObjectTester\DataObjects\ClassExpectation;
use Sytzez\DataObjectTester\Factories\PropertyExpectationFactory;
use Sytzez\DataObjectTester\Tests\TestHelpers\DataClass;
use Sytzez\DataObjectTester\Tests\TestHelpers\EmptyClass;

class ClassExpectationBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_create_an_empty_class_expectation(): void
    {
        $result = (new ClassExpectationBuilder(EmptyClass::class))
            ->getResult();

        static::assertEquals(EmptyClass::class, $result->getFqn());
        static::assertEmpty($result->getPropertyExpectations());
    }

    /**
     * @test
     */
    public function it_can_create_a_class_expectation_with_property_expectations(): void
    {
        $properties = [
            PropertyExpectationFactory::create('getString', ['a', 'b']),
            PropertyExpectationFactory::create('getInt', [1, 2, 3]),
            PropertyExpectationFactory::create('getArray', [[]]),
        ];

        $result = (new ClassExpectationBuilder(DataClass::class))
            ->addPropertyExpectation($properties[0])
            ->addPropertyExpectation($properties[1])
            ->addPropertyExpectation($properties[2])
            ->getResult();

        static::assertEquals(DataClass::class, $result->getFqn());
        static::assertCount(3, $result->getPropertyExpectations());
        static::assertEquals($properties[0], $result->getPropertyExpectations()[0]);
        static::assertEquals($properties[1], $result->getPropertyExpectations()[1]);
        static::assertEquals($properties[2], $result->getPropertyExpectations()[2]);
    }
}
