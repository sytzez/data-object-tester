<?php

namespace Sytzez\DataObjectTester\Tests\DataObjects;

use Sytzez\DataObjectTester\DataObjects\ClassExpectation;
use Sytzez\DataObjectTester\DataObjects\PropertyExpectation;
use Sytzez\DataObjectTester\DataObjectTestCase;
use Sytzez\DataObjectTester\Generators\MaximalCaseGenerator;
use Sytzez\DataObjectTester\PropertyCases\ConstructorExceptionPropertyCase;
use Sytzez\DataObjectTester\PropertyCases\DefaultPropertyCase;
use Sytzez\DataObjectTester\PropertyCases\SimplePropertyCase;

class PropertyExpectationTest extends DataObjectTestCase
{
    /**
     * @test
     */
    public function it_returns_the_right_values(): void
    {
        $this->testDataObjects(
            ClassExpectation::create(PropertyExpectation::class, [
                'getGetterName' => ['getThis', 'isThat'],
                'getCases' => [
                    new ConstructorExceptionPropertyCase([], 'PropertyExpectation must contain at least one PropertyCase'),
                    [new SimplePropertyCase(1)],
                    [new SimplePropertyCase(1), new SimplePropertyCase(2)],
                ]
            ]),
            new MaximalCaseGenerator()
        );
    }

    /**
     * @test
     */
    public function it_can_get_the_default_case(): void
    {
        $defaultPropertyCase = new DefaultPropertyCase(0);

        $propertyExpectation = new PropertyExpectation('getSomething', [
            new SimplePropertyCase(1),
            new SimplePropertyCase(2),
            $defaultPropertyCase,
            new SimplePropertyCase(3),
        ]);

        static::assertEquals($defaultPropertyCase, $propertyExpectation->getDefaultCase());
    }

    /**
     * @test
     */
    public function it_returns_null_if_there_is_no_default_case(): void
    {
        $propertyExpectation = new PropertyExpectation('getSomething', [
            new SimplePropertyCase(1),
            new SimplePropertyCase(2),
            new SimplePropertyCase(3),
        ]);

        static::assertNull($propertyExpectation->getDefaultCase());
    }
}
