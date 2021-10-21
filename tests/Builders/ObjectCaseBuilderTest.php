<?php

namespace Sytzez\DataObjectTester\Tests\Builders;

use PHPUnit\Framework\TestCase;
use Sytzez\DataObjectTester\Builders\ObjectCaseBuilder;
use Sytzez\DataObjectTester\DataObjects\ClassExpectation;
use Sytzez\DataObjectTester\Tests\TestHelpers\DataClass;
use Sytzez\DataObjectTester\Tests\TestHelpers\EmptyClass;

class ObjectCaseBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_create_an_empty_object_case(): void
    {
        $classExpectation = ClassExpectation::create(EmptyClass::class, []);

        $result = (new ObjectCaseBuilder($classExpectation))
            ->getResult();

        static::assertEquals($classExpectation, $result->getClassExpectation());
        static::assertEmpty($result->getPropertyCases());
    }

    /**
     * @test
     */
    public function it_can_create_an_object_case_with_property_cases(): void
    {
        $classExpectation = ClassExpectation::create(DataClass::class, [
            'getString' => ['a', 'b', 'c'],
            'getInt'    => [1, 2, 3],
            'getArray'  => [[]],
        ]);

        $propertyCases = [
            $classExpectation->getPropertyExpectations()[0]->getCases()[0],
            $classExpectation->getPropertyExpectations()[1]->getCases()[0],
            $classExpectation->getPropertyExpectations()[2]->getCases()[0],
        ];

        $result = (new ObjectCaseBuilder($classExpectation))
            ->addPropertyCase($propertyCases[0])
            ->addPropertyCase($propertyCases[1])
            ->addPropertyCase($propertyCases[2])
            ->getResult();

        static::assertEquals($classExpectation, $result->getClassExpectation());
        static::assertCount(3, $result->getPropertyCases());
        static::assertEquals($propertyCases[0], $result->getPropertyCases()[0]);
        static::assertEquals($propertyCases[1], $result->getPropertyCases()[1]);
        static::assertEquals($propertyCases[2], $result->getPropertyCases()[2]);
    }
}
