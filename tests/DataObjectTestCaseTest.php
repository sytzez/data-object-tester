<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Tests;

use Sytzez\DataObjectTester\DataObjects\ClassExpectation;
use Sytzez\DataObjectTester\DataObjectTestCase;
use Sytzez\DataObjectTester\Generators\MaximalCaseGenerator;
use Sytzez\DataObjectTester\Generators\MinimalCaseGenerator;
use Sytzez\DataObjectTester\PropertyCases\ConstructorExceptionPropertyCase;
use Sytzez\DataObjectTester\PropertyCases\DefaultPropertyCase;
use Sytzez\DataObjectTester\PropertyCases\TransformativePropertyCase;
use Sytzez\DataObjectTester\Tests\TestHelpers\DataClass;
use Sytzez\DataObjectTester\Tests\TestHelpers\DataClassWithDefaults;
use Sytzez\DataObjectTester\Tests\TestHelpers\TransformativeDataClass;
use Sytzez\DataObjectTester\Tests\TestHelpers\ValidatedDataClass;

class DataObjectTestCaseTest extends DataObjectTestCase
{
    /**
     * @test
     */
    public function it_tests_data_objects(): void
    {
        $this->testDataObjects(
            ClassExpectation::create(DataClass::class, [
                'getString' => ['a', 'b', 'c'],
                'getInt'    => [1, 2, 3],
                'getArray'  => [[], [1, 2, 3]],
            ]),
        );

        $getterCount = 3;
        $expectedObjectCaseCount = 3;
        $expectedGettersExistAssertCount = $getterCount;
        $expectedGetterOutputAssertCount = $getterCount * $expectedObjectCaseCount;
        $expectedAssertCount = $expectedGettersExistAssertCount + $expectedGetterOutputAssertCount;

        static::assertEquals($expectedAssertCount, static::getCount());
    }

    /**
     * @test
     */
    public function it_tests_data_objects_using_a_specified_generator(): void
    {
        $this->testDataObjects(
            ClassExpectation::create(DataClass::class, [
                'getString' => ['a', 'b', 'c'],
                'getInt'    => [1, 2, 3],
                'getArray'  => [[], [1, 2, 3]],
            ]),
            new MaximalCaseGenerator(),
        );

        $getterCount = 3;
        $expectedObjectCaseCount = 3 * 3 * 2;
        $expectedGettersExistAssertCount = $getterCount;
        $expectedGetterOutputAssertCount = $getterCount * $expectedObjectCaseCount;
        $expectedAssertCount = $expectedGettersExistAssertCount + $expectedGetterOutputAssertCount;

        static::assertEquals($expectedAssertCount, static::getCount());
    }

    /**
     * @test
     */
    public function it_tests_transformative_data_objects(): void
    {
        $this->testDataObjects(
            ClassExpectation::create(TransformativeDataClass::class, [
                'getString' => [
                    new TransformativePropertyCase('a', 'aa'),
                    new TransformativePropertyCase('b', 'bb'),
                ],
                'getInt'    => [
                    new TransformativePropertyCase(1, 2),
                    new TransformativePropertyCase(3, 6),
                ],
                'getArray'  => [
                    new TransformativePropertyCase([], []),
                    new TransformativePropertyCase([1, 2, 3], [1, 2, 3, 1, 2, 3]),
                ],
            ]),
        );

        $getterCount = 3;
        $expectedObjectCaseCount = 2;
        $expectedGettersExistAssertCount = $getterCount;
        $expectedGetterOutputAssertCount = $getterCount * $expectedObjectCaseCount;
        $expectedAssertCount = $expectedGettersExistAssertCount + $expectedGetterOutputAssertCount;

        static::assertEquals($expectedAssertCount, static::getCount());
    }

    /**
     * @test
     */
    public function it_tests_validation_exceptions(): void
    {
        $this->testDataObjects(
            ClassExpectation::create(ValidatedDataClass::class, [
                'getString' => [
                    'a',
                    'aa',
                    'aaa',
                    new ConstructorExceptionPropertyCase('aaaa', ValidatedDataClass::STRING_TOO_LONG),
                ],
                'getInt'    => [
                    0,
                    1,
                    PHP_INT_MAX,
                    new ConstructorExceptionPropertyCase(-1, ValidatedDataClass::NEGATIVE_INT),
                ],
            ]),
        );
    }

    /**
     * @test
     */
    public function it_tests_default_values(): void
    {
        $this->testDataObjects(
            ClassExpectation::create(DataClassWithDefaults::class, [
                'getString' => [
                    'a',
                    'b',
                    new DefaultPropertyCase(DataClassWithDefaults::DEFAULT_STRING),
                ],
                'getInt'    => [
                    0,
                    1,
                    new DefaultPropertyCase(DataClassWithDefaults::DEFAULT_INT),
                ],
            ]),
            new MaximalCaseGenerator()
        );

        $this->testDataObjects(
            ClassExpectation::create(DataClassWithDefaults::class, [
                'getString' => [
                    'a',
                    new DefaultPropertyCase(DataClassWithDefaults::DEFAULT_STRING),
                    'b',
                ],
                'getInt'    => [
                    0,
                    1,
                    new DefaultPropertyCase(DataClassWithDefaults::DEFAULT_INT),
                ],
            ]),
            new MinimalCaseGenerator()
        );
    }
}
