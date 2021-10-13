<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Contracts\Strategies;

use Sytzez\DataObjectTester\DataObjects\ClassDescription;
use Sytzez\DataObjectTester\DataObjects\ObjectCase;

interface CaseGeneratorStrategy
{
    /**
     * @param ClassDescription $classDescription
     * @return iterable<ObjectCase>
     */
    public function generate(ClassDescription $classDescription): iterable;
}
