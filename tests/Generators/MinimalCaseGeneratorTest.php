<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Tests\Generators;

use Sytzez\DataObjectTester\DataObjects\ClassExpectation;
use Sytzez\DataObjectTester\DataObjects\ObjectCase;
use Sytzez\DataObjectTester\Generators\MinimalCaseGenerator;
use Sytzez\DataObjectTester\Tests\TestHelpers\DataClass;
use Sytzez\DataObjectTester\Tests\TestHelpers\EmptyClass;
use Sytzez\DataObjectTester\Tests\TestHelpers\GeneratorToArray;

class MinimalCaseGeneratorTest extends CaseGeneratorTestCase
{
    /**
     * @test
     */
    public function it_generates_no_cases_if_the_object_has_no_properties(): void
    {
        $classExpectation = ClassExpectation::create(EmptyClass::class, []);

        $generator = new MinimalCaseGenerator();

        $cases = GeneratorToArray::convert($generator->generate($classExpectation));

        static::assertCount(0, $cases);
    }

    /**
     * @test
     */
    public function it_creates_as_few_possibilities_as_possible(): void
    {
        $classExpectation = ClassExpectation::create(DataClass::class, [
            'getString' => ['a', 'b', 'c'],
            'getInt'    => [1, -1],
            'getArray'  => [[]],
        ]);

        $generator = new MinimalCaseGenerator();

        $cases = GeneratorToArray::convert($generator->generate($classExpectation));

        static::assertCount(3, $cases);

        static::assertContainsObjectCase(
            ObjectCase::create($classExpectation, [
                'getString' => 'a',
                'getInt'    => 1,
                'getArray'  => [],
            ]),
            $cases
        );

        static::assertContainsObjectCase(
            ObjectCase::create($classExpectation, [
                'getString' => 'b',
                'getInt'    => -1,
                'getArray'  => [],
            ]),
            $cases
        );

        static::assertContainsObjectCase(
            ObjectCase::create($classExpectation, [
                'getString' => 'c',
                'getInt'    => 1,
                'getArray'  => [],
            ]),
            $cases
        );
    }
}
