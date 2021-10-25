<?php

namespace Sytzez\DataObjectTester\Tests\DataObjects;

use Sytzez\DataObjectTester\DataObjects\ClassExpectation;
use Sytzez\DataObjectTester\DataObjects\InstantiateObjectResult;
use Sytzez\DataObjectTester\DataObjectTestCase;
use Sytzez\DataObjectTester\Generators\MaximalCaseGenerator;
use Sytzez\DataObjectTester\PropertyCases\DefaultPropertyCase;
use Sytzez\DataObjectTester\Tests\TestHelpers\DataClass;
use Sytzez\DataObjectTester\Tests\TestHelpers\EmptyClass;

class InstantiateObjectResultTest extends DataObjectTestCase
{
    /**
     * @test
     */
    public function it_returns_the_right_values(): void
    {
        $this->testDataObjects(
            ClassExpectation::create(InstantiateObjectResult::class, [
                'getObject' => [
                    null,
                    new EmptyClass(),
                    new DataClass('a', 1, []),
                    new DefaultPropertyCase(null),
                ],
                'exceptionWasCaught' => [
                    true,
                    false,
                    new DefaultPropertyCase(false),
                ],
            ]),
            new MaximalCaseGenerator(),
        );
    }
}
