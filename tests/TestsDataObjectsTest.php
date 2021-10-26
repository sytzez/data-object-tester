<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Tests;

use Sytzez\DataObjectTester\DataObjects\ClassExpectation;
use Sytzez\DataObjectTester\Tests\TestHelpers\DataClass;
use Sytzez\DataObjectTester\TestsDataObjects;
use PHPUnit\Framework\TestCase;

class TestsDataObjectsTest extends TestCase
{
    use TestsDataObjects;

    /**
     * @test
     */
    public function it_can_test_data_objects(): void
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
    public function it_throws_an_exception_if_the_class_does_not_extend_test_case(): void
    {
        $test = new class {
            use TestsDataObjects;

            public function run(): void
            {
                $this->testDataObjects(
                    ClassExpectation::create(DataClass::class, [
                        'getString' => ['a', 'b', 'c'],
                        'getInt'    => [1, 2, 3],
                        'getArray'  => [[], [1, 2, 3]],
                    ]),
                );
            }
        };

        static::expectExceptionMessage('This trait must be used on an instance of ' . TestCase::class);

        $test->run();
    }
}
