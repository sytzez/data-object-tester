<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester;

use PHPUnit\Framework\TestCase;
use Sytzez\DataObjectTester\Contracts\Generators\CaseGeneratorStrategy;
use Sytzez\DataObjectTester\DataObjects\ClassExpectation;

abstract class DataObjectTestCase extends TestCase
{
    protected function testDataObjects(
        ClassExpectation $classExpectation,
        ?CaseGeneratorStrategy $caseGenerator = null,
    ): void {
        $tester = new DataObjectTester($this, $classExpectation, $caseGenerator);

        $tester->test();
    }
}
