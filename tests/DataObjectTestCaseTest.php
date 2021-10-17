<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Tests;

use Sytzez\DataObjectTester\DataObjects\ClassExpectation;
use Sytzez\DataObjectTester\DataObjects\InputOutputExpectation;
use Sytzez\DataObjectTester\DataObjectTestCase;
use Sytzez\DataObjectTester\Generators\MaximalCaseGenerator;
use Sytzez\DataObjectTester\PropertyCases\TransformativePropertyCase;
use Sytzez\DataObjectTester\Tests\TestHelpers\DataClass;
use Sytzez\DataObjectTester\Tests\TestHelpers\TransformativeDataClass;

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
}
