<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Tests\Strategies\CaseGenerators;

use Sytzez\DataObjectTester\DataObjects\ClassDescription;
use Sytzez\DataObjectTester\DataObjects\ObjectCase;
use Sytzez\DataObjectTester\Strategies\CaseGenerators\MinimalCaseGenerator;
use Sytzez\DataObjectTester\Tests\TestHelpers\DataClass;
use Sytzez\DataObjectTester\Tests\TestHelpers\EmptyClass;

class MinimalCaseGeneratorTest extends CaseGeneratorTestCase
{
    /**
     * @test
     */
    public function it_generates_no_cases_if_the_object_has_no_properties(): void
    {
        $classDescription = ClassDescription::create(EmptyClass::class, []);

        $generator = new MinimalCaseGenerator();

        $cases = static::iteratorToArray($generator->generate($classDescription));

        static::assertCount(0, $cases);
    }

    /**
     * @test
     */
    public function it_creates_as_few_possibilities_as_possible(): void
    {
        $classDescription = ClassDescription::create(DataClass::class, [
            'getString' => ['a', 'b', 'c'],
            'getInt'    => [1, -1],
            'getArray'  => [[]],
        ]);

        $generator = new MinimalCaseGenerator();

        $cases = static::iteratorToArray($generator->generate($classDescription));

        static::assertCount(3, $cases);
    }
}
