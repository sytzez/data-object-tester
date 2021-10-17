<?php

namespace Sytzez\DataObjectTester\Tests\DataObjects;

use Sytzez\DataObjectTester\DataObjects\ClassExpectation;
use Sytzez\DataObjectTester\DataObjects\InputOutputExpectation;
use Sytzez\DataObjectTester\DataObjects\PropertyCase;
use Sytzez\DataObjectTester\DataObjectTestCase;

class PropertyCaseTest extends DataObjectTestCase
{
    /**
     * @test
     */
    public function it_returns_the_right_values(): void
    {
        $inputOutputExpectation = new InputOutputExpectation('in', 'out');

        $this->testDataObjects(
            ClassExpectation::create(PropertyCase::class, [
                'getGetterName' => [
                    'getThing',
                    'getAnotherThing',
                    'isTrue',
                ],
                'getExpectation' => [
                    new InputOutputExpectation(
                        $inputOutputExpectation,
                        $inputOutputExpectation
                    ),
                ],
            ])
        );
    }
}
