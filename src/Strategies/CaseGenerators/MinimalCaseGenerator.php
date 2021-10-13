<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Strategies\CaseGenerators;

use Sytzez\DataObjectTester\Contracts\Strategies\CaseGeneratorStrategy;
use Sytzez\DataObjectTester\DataObjects\ClassDescription;
use Sytzez\DataObjectTester\DataObjects\ObjectCase;

final class MinimalCaseGenerator implements CaseGeneratorStrategy
{
    /**
     * @param ClassDescription $classDescription
     * @return iterable<ObjectCase>
     */
    public function generate(ClassDescription $classDescription): iterable
    {
        return [];
    }
}
