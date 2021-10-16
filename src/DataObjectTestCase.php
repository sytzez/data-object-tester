<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester;

use PHPUnit\Framework\TestCase;
use Sytzez\DataObjectTester\Contracts\Strategies\CaseGeneratorStrategy;
use Sytzez\DataObjectTester\DataObjects\ClassDescription;

abstract class DataObjectTestCase extends TestCase
{
    protected ClassDescription $dataClassDescription;

    protected function setDataClassDescription(string $fqn, array $description): void
    {
        $this->dataClassDescription = ClassDescription::create($fqn, $description);
    }

    protected function testDataObjects(?CaseGeneratorStrategy $caseGenerator = null): void
    {
        $tester = new DataObjectTester($this, $this->dataClassDescription, $caseGenerator);

        $tester->test();
    }
}
