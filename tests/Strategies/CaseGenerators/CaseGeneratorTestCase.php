<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Tests\Strategies\CaseGenerators;

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
                $propA->getInput() !== $propB->getInput()
                || $propA->getExpectedOutput() !== $propB->getExpectedOutput()
            ) {
                return false;
            }
        }

        return true;
    }
}
