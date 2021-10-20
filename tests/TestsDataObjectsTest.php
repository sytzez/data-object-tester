<?php

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
}
