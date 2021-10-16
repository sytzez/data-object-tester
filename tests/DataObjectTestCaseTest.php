<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Tests;

use Sytzez\DataObjectTester\DataObjects\ClassDescription;
use Sytzez\DataObjectTester\DataObjects\InputOutputPair;
use Sytzez\DataObjectTester\DataObjectTestCase;
use Sytzez\DataObjectTester\Strategies\CaseGenerators\MaximalCaseGenerator;
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
            ClassDescription::create(DataClass::class, [
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
            ClassDescription::create(DataClass::class, [
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
            ClassDescription::create(TransformativeDataClass::class, [
                'getString' => [
                    new InputOutputPair('a', 'aa'),
                    new InputOutputPair('b', 'bb'),
                ],
                'getInt'    => [
                    new InputOutputPair(1, 2),
                    new InputOutputPair(3, 6),
                ],
                'getArray'  => [
                    new InputOutputPair([], []),
                    new InputOutputPair([1, 2, 3], [1, 2, 3, 1, 2, 3]),
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
