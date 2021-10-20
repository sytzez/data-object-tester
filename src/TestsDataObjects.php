<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester;

use Sytzez\DataObjectTester\Contracts\Generators\CaseGeneratorStrategy;
use Sytzez\DataObjectTester\DataObjects\ClassExpectation;

trait TestsDataObjects
{
    protected function testDataObjects(
        ClassExpectation $classExpectation,
        ?CaseGeneratorStrategy $caseGenerator = null,
    ): void {
        $tester = new DataObjectTester($this, $classExpectation, $caseGenerator);

        $tester->run();
    }
}
