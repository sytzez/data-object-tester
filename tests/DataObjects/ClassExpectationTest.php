<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Tests\DataObjects;

use Sytzez\DataObjectTester\DataObjects\ClassExpectation;
use Sytzez\DataObjectTester\DataObjects\PropertyExpectation;
use Sytzez\DataObjectTester\DataObjectTestCase;
use Sytzez\DataObjectTester\Generators\MaximalCaseGenerator;
use Sytzez\DataObjectTester\PropertyCases\ConstructorExceptionPropertyCase;
use Sytzez\DataObjectTester\PropertyCases\DefaultPropertyCase;
use Sytzez\DataObjectTester\Tests\TestHelpers\ConstructorThrowsException;
use Sytzez\DataObjectTester\Tests\TestHelpers\DataClass;
use Sytzez\DataObjectTester\Tests\TestHelpers\TransformativeDataClass;

class ClassExpectationTest extends DataObjectTestCase
{
    /**
     * @test
     */
    public function it_returns_the_right_values(): void
    {
        $this->testDataObjects(
            ClassExpectation::create(ClassExpectation::class, [
                'getFqn'                  => [
                    DataClass::class,
                    TransformativeDataClass::class,
                    ConstructorThrowsException::class,
                    new ConstructorExceptionPropertyCase(
                        'SantaClass',
                        'Class does not exist'
                    ),
                ],
                'getPropertyExpectations' => [
                    [],
                    [
                        PropertyExpectation::create('getterA', ['a', 'b', 'c']),
                        PropertyExpectation::create('getterB', [1, 2, new DefaultPropertyCase(0)]),
                    ]
                ]
            ]),
            new MaximalCaseGenerator(),
        );
    }

    /**
     * @test
     */
    public function it_checks_if_all_properties_have_a_default_after_the_first_default(): void
    {
        static::expectExceptionMessage(DataClass::class . '::getArray() has no default case');

        new ClassExpectation(
            DataClass::class,
            [
                PropertyExpectation::create('getInt', [1, 2, 3]),
                PropertyExpectation::create('getString', [
                    'a',
                    new DefaultPropertyCase(''),
                ]),
                PropertyExpectation::create('getArray', [[], [1, 2, 3]]),
            ]
        );
    }
}
