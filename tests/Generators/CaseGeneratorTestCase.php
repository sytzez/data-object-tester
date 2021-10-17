<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Tests\Generators;

use Generator;
use PHPUnit\Framework\TestCase;
use Sytzez\DataObjectTester\DataObjects\ObjectCase;

abstract class CaseGeneratorTestCase extends TestCase
{
    /**
     * @param ObjectCase $expected
     * @param array<ObjectCase> $cases
     */
    protected static function assertContainsObjectCase(ObjectCase $expected, array $cases): void
    {
        foreach ($cases as $case) {
            if (static::objectCasesAreEqual($expected, $case)) {
                return;
            }
        }

        static::fail('Expected object case not found');
    }

    protected static function objectCasesAreEqual(ObjectCase $a, ObjectCase $b): bool
    {
        if (count($a->getPropertyCases()) !== count($b->getPropertyCases())) {
            return false;
        }

        for ($i = 0; $i < count($a->getPropertyCases()); $i++) {
            $propA = $a->getPropertyCases()[$i];
            $propB = $b->getPropertyCases()[$i];

            if (
                $propA->getExpectation()->getInput() !== $propB->getExpectation()->getInput()
                || $propA->getExpectation()->getExpectedOutput() !== $propB->getExpectation()->getExpectedOutput()
            ) {
                return false;
            }
        }

        return true;
    }

    protected static function generatorToArray(Generator $generator): array {
        $array = [];

        foreach ($generator as $generated) {
            $array[] = $generated;
        }

        return $array;
    }
}
