<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Tests\Generators;

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
                iterator_to_array($propA->getExpectation()->getConstructorArguments())
                    !== iterator_to_array($propB->getExpectation()->getConstructorArguments())
                || $propA->getExpectation()->getExpectedOutput() !== $propB->getExpectation()->getExpectedOutput()
            ) {
                return false;
            }
        }

        return true;
    }
}
