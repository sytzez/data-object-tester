<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester;

use PHPUnit\Framework\TestCase;
use Sytzez\DataObjectTester\Contracts\Strategies\CaseGeneratorStrategy;
use Sytzez\DataObjectTester\DataObjects\ClassDescription;

abstract class DataObjectTestCase extends TestCase
{
    protected function testDataObjects(
        ClassDescription $classDescription,
        ?CaseGeneratorStrategy $caseGenerator = null,
    ): void {
        $tester = new DataObjectTester($this, $classDescription, $caseGenerator);

        $tester->test();
    }
}
