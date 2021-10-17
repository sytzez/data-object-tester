<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Tests\DataObjects;

use Sytzez\DataObjectTester\DataObjects\ClassExpectation;
use Sytzez\DataObjectTester\DataObjects\InputOutputExpectation;
use PHPUnit\Framework\TestCase;
use Sytzez\DataObjectTester\DataObjectTestCase;
use Sytzez\DataObjectTester\Generators\MaximalCaseGenerator;
use Sytzez\DataObjectTester\Tests\TestHelpers\DataClass;

class InputOutputExpectationTest extends DataObjectTestCase
{
    /**
     * @test
     */
    public function it_returns_the_right_values(): void
    {
        $values = [
            1,
            1.5,
            'a',
            true,
            false,
            null,
            [],
            new DataClass('a', 1, []),
        ];

        $constructorArgumentExpectations = array_map(
            fn ($value) => new InputOutputExpectation($value, [$value]),
            $values
        );

        $this->testDataObjects(
            ClassExpectation::create(InputOutputExpectation::class, [
                'getConstructorArguments' => $constructorArgumentExpectations,
                'getExpectedOutput'       => $values,
            ]),
            new MaximalCaseGenerator()
        );
    }
}
