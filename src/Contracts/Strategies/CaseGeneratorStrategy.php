<?php

declare(strict_types=1);

namespace Sytzez\DataObjectTester\Contracts\Strategies;

use Generator;
use Sytzez\DataObjectTester\DataObjects\ClassDescription;
use Sytzez\DataObjectTester\DataObjects\ObjectCase;

interface CaseGeneratorStrategy
{
    /**
     * @param ClassDescription $classDescription
     * @return Generator<ObjectCase>
     */
    public function generate(ClassDescription $classDescription): Generator;
}
