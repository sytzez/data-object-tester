<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Tests\Strategies\CaseGenerators;

use Sytzez\DataObjectTester\DataObjects\ClassExpectation;
use Sytzez\DataObjectTester\DataObjects\ObjectCase;
use Sytzez\DataObjectTester\Strategies\CaseGenerators\MaximalCaseGenerator;
use Sytzez\DataObjectTester\Tests\TestHelpers\DataClass;
use Sytzez\DataObjectTester\Tests\TestHelpers\EmptyClass;

class MaximalCaseGeneratorTest extends CaseGeneratorTestCase
{
    /**
     * @test
     */
    public function it_generates_one_empty_case_if_the_object_has_no_properties(): void
    {
        $classExpectation = ClassExpectation::create(EmptyClass::class, []);

        $generator = new MaximalCaseGenerator();

        $cases = static::generatorToArray($generator->generate($classExpectation));

        static::assertCount(1, $cases);
        static::assertEquals($classExpectation, $cases[0]->getClassExpectation());
        static::assertEmpty($cases[0]->getPropertyCases());
    }

    /**
     * @test
     */
    public function it_creates_all_possible_combinations_of_properties(): void
    {
        $classExpectation = ClassExpectation::create(DataClass::class, [
            'getString' => ['a', 'b', 'c'],
            'getInt'    => [1, -1],
            'getArray'  => [[]],
        ]);

        $generator = new MaximalCaseGenerator();

        $cases = static::generatorToArray($generator->generate($classExpectation));

        static::assertCount(3 * 2 * 1, $cases);

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

    /**
     * @test
     */
    public function it_can_limit_the_amount_of_generated_cases(): void
    {
        $classExpectation = ClassExpectation::create(DataClass::class, [
            'getString' => ['a', 'b', 'c'],
            'getInt'    => [1, -1],
            'getArray'  => [[]],
        ]);

        $generator = new MaximalCaseGenerator(3);

        $cases = static::generatorToArray($generator->generate($classExpectation));

        static::assertCount(3, $cases);

        // TODO: generate variations of all fields first, instead of doing just one field, in case the amount is limited
    }
}
