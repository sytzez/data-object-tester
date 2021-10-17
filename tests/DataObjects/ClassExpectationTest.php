<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Tests\DataObjects;

use Sytzez\DataObjectTester\DataObjects\ClassExpectation;
use Sytzez\DataObjectTester\DataObjects\PropertyExpectation;
use Sytzez\DataObjectTester\DataObjectTestCase;
use Sytzez\DataObjectTester\Generators\MaximalCaseGenerator;
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
                    ConstructorThrowsException::class
                ],
                'getPropertyExpectations' => [
                    [],
                    [
                        PropertyExpectation::create('getterA', ['a', 'b', 'c']),
                        PropertyExpectation::create('getterB', [1, 2, 3]),
                    ]
                ]
            ]),
            new MaximalCaseGenerator(),
        );
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_the_class_does_not_exist(): void
    {
        static::expectExceptionMessage('Class does not exist');

        new ClassExpectation('does not exist', []);
    }
}
