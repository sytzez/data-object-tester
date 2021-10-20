<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester;

use DomainException;
use PHPUnit\Framework\TestCase;
use Sytzez\DataObjectTester\Contracts\Generators\CaseGeneratorStrategy;
use Sytzez\DataObjectTester\DataObjects\ClassExpectation;

trait TestsDataObjects
{
    protected function testDataObjects(
        ClassExpectation $classExpectation,
        ?CaseGeneratorStrategy $caseGenerator = null,
    ): void {
        if (! $this instanceof TestCase) {
            throw new DomainException('This trait must be used on an instance of ' . TestCase::class);
        }

        $tester = new DataObjectTester($this, $classExpectation, $caseGenerator);

        $tester->run();
    }
}
